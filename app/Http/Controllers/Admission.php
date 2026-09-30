<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\AdmissionStep;
use App\Models\Classe;
use App\Models\File as FileModel;
use App\Models\Country;
use App\Models\Nationality;
use App\Models\ParentModel;
use App\Models\Academic_year;
use App\Models\Relationship;
use App\Models\StudentDetail;
use App\Models\Medical;
use App\Models\Emergency;
use App\Models\ChildPickup;
use App\Models\Commitment;
use App\Models\LearningBehavioural;
use App\Models\Document;
use App\Models\ReqFile;
use App\Models\FileLevel;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Admission extends Controller
{
    /**
     * Vérifier si l'utilisateur est authentifié (double vérification côté contrôleur).
     * Si non, rediriger vers la page login.
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    private function checkAuth()
    {
        if (!FacadesSession::has('user')) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.')
                ->with('intended', request()->url());
        }
    }

    /**
     * Obtenir les données utilisateur de la session
     */
    private function getAuthUser()
    {
        if (!FacadesSession::has('user')) {
            return null;
        }
        return FacadesSession::get('user');
    }

    /**
     * Vérifier que l'étudiant appartient à l'utilisateur (id_userS).
     * Retourne le StudentDetail ou null si non trouvé / pas le propriétaire.
     */
    private function getStudentForUser(string $codeStudent, $userId): ?StudentDetail
    {
        if (empty($userId)) {
            return null;
        }
        return StudentDetail::where('code_student', $codeStudent)->where('id_userS', $userId)->first();
    }

    /**
     * Find an existing admission for the same child in the active academic year.
     */
    private function findExistingChildAdmission($userId, string $academicYear, string $firstName, string $lastName, string $birthday): ?StudentDetail
    {
        return StudentDetail::query()
            ->join('academic_year', 'academic_year.code', '=', 'StudentDetails.code_academic')
            ->where('StudentDetails.id_userS', $userId)
            ->where('academic_year.id_user', $userId)
            ->where('academic_year.academicyear', $academicYear)
            ->whereIn('academic_year.registration', ['in_process', 'process_end', 'pending'])
            ->whereDate('StudentDetails.birthday', $birthday)
            ->whereRaw('LOWER(TRIM(StudentDetails.prenom)) = ?', [strtolower(trim($firstName))])
            ->whereRaw('LOWER(TRIM(StudentDetails.nom)) = ?', [strtolower(trim($lastName))])
            ->select('StudentDetails.*')
            ->orderByDesc('StudentDetails.id_stud')
            ->first();
    }

    /**
     * Vérifier ownership et retourner l'étudiant ; sinon réponse JSON 403.
     */
    private function ensureStudentOwnership(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $userId = $request->input('id_us') ?? $request->input('user_id') ?? FacadesSession::get('user.id');
        $student = $this->getStudentForUser($request->code_student ?? '', $userId);
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found or you are not allowed to modify this admission.',
            ], 403);
        }
        return null;
    }

    public function get_all_HomeAdmission(Request $request)
    {
        // Vérifier l'authentification
        $auth = $this->checkAuth();
        if ($auth) return $auth;

        // user_id from request (POST from "Back to list" form) or from session (GET from sidebar/direct)
        $userId = $request->input('user_id') ?? session('user.id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session expired. Please sign in again.');
        }

        $acaYear = AcademicYear::where('etat', 1)->first();
        if (!$acaYear) {
            return redirect()->back()->with('error', 'No active academic year found.');
        }

        $classe = Classe::where('aca_year', $acaYear->year)->get();
        $user = User::where('id_us', $userId)->first();

        $admilist = StudentDetail::query()
            ->join('admission_step', 'admission_step.code_stud', '=', 'StudentDetails.code_student')
            ->join('academic_year', 'academic_year.code', '=', 'StudentDetails.code_academic')
            ->leftJoin('classes', function ($join) {
                $join->on('classes.name_classe', '=', 'academic_year.class_current')
                    ->on('classes.aca_year', '=', 'academic_year.academicyear');
            })
            ->leftJoin('subLevel', 'subLevel.id', '=', 'classes.id_subLevel')
            ->leftJoin('level', 'level.id', '=', 'subLevel.id_level')
            ->where('StudentDetails.id_userS', $userId)
            ->where('admission_step.academicyear', $acaYear->year)
            ->select(
                'StudentDetails.*',
                'admission_step.step',
                'admission_step.academicyear',
                'academic_year.class_current',
                'academic_year.registration',
                DB::raw('academic_year.class_current as class_required'),
                DB::raw('COALESCE(academic_year.level, level.level_name) as class_level')
            )
            ->orderBy('StudentDetails.id_stud', 'desc')
            ->get();

        // Count by registration status for this user (id_userS)
        $baseQuery = StudentDetail::query()
            ->join('admission_step', 'admission_step.code_stud', '=', 'StudentDetails.code_student')
            ->join('academic_year', 'academic_year.code', '=', 'StudentDetails.code_academic')
            ->where('StudentDetails.id_userS', $userId)
            ->where('admission_step.academicyear', $acaYear->year);

        $count_in_process = (clone $baseQuery)->where('academic_year.registration', 'in_process')->count();
        $count_completed   = (clone $baseQuery)->where('academic_year.registration', 'process_end')->count();
        $count_pending     = (clone $baseQuery)->where('academic_year.registration', 'pending')->count();
        $count_rejected    = (clone $baseQuery)->where('academic_year.registration', 'rejected')->count();

        $data = [
            'acayear' => $acaYear,
            'classes' => $classe,
            'admilists' => $admilist,
            'users' => $user,
            'count_in_process' => $count_in_process,
            'count_completed' => $count_completed,
            'count_pending' => $count_pending,
            'count_rejected' => $count_rejected,
        ];

        return view('admissions.home-admission')->with($data);
    }

    /**
     * API: retourne les données de toutes les admissions (JSON).
     * GET /api/admissions
     */
    public function getAllAdmissionsApi(Request $request)
    {
        $admissions = StudentDetail::query()
            ->with([
                'admissionStep',
                'academicYear',
                'medical',
                'emergency',
                'childPickup',
                'commitment',
                'files',
                'father',
                'mother',
                'guardian',
                'user:id_us,first_name,email',
            ])
            ->orderBy('id_stud', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'count'   => $admissions->count(),
            'data'    => $admissions,
        ]);
    }

    /**
     * API: modifier le status d'un enregistrement academic_year.
     * GET /api/academic-years/{code}/{status}
     *
     * {code} correspond a la colonne academic_year.code.
     * {status} correspond a la colonne academic_year.registration.
     */
    public function updateAcademicYearApi($code, $status)
    {
        $acaYear = AcademicYear::where('etat', 1)->first();
        $academicYear = Academic_year::query()
            ->where('code', $code)
            ->where('academicyear', $acaYear->year)
            ->first();

        if (!$academicYear) {
            return response()->json([
                'success' => false,
                'message' => 'Academic year record not found.',
            ], 404);
        }

        DB::table('academic_year')
        ->where('code', $code)
        ->where('academicyear', $acaYear->year)
        ->update(['registration' => $status]);

        return response()->json([
            'success' => true,
            'message' => 'Academic year status updated successfully.',
            'data' => $academicYear->fresh(),
        ]);
    }

        public function updateAcademicYearApiByParent($code, $status)
    {
         $acaYear = AcademicYear::where('etat', 1)->first();
        $academicYear = Academic_year::query()
            ->where('codeFather', $code)
            ->orWhere('codeMother', $code)
            ->orWhere('codeGuardian', $code)
            ->where('registration', 'Waiting_Installment')
            ->where('academicyear', $acaYear->year)
            ->first();

        if (!$academicYear) {
            return response()->json([
                'success' => false,
                'message' => 'Academic year record not found.',
            ], 404);
        }

        DB::table('academic_year')
        ->where('codeFather', $code)
        ->orWhere('codeMother', $code)
        ->orWhere('codeGuardian', $code)
        ->where('registration', 'Waiting_Installment')
        ->where('academicyear', $acaYear->year)
        ->update(['registration' => $status]);

        return response()->json([
            'success' => true,
            'message' => 'Academic year status updated successfully.',
            'data' => $academicYear->fresh(),
        ]);
    }

    public function get_Redirection(Request $request)
    {
        // Vérifier l'authentification
        $auth = $this->checkAuth();
        if ($auth) return $auth;

        try {
            $codeStud = $request->code_student;

            if (empty($codeStud)) {
                return redirect('admission')->with([
                    'localstorage_data' => json_encode([
                        'current_step' => 'step1',
                        'code_student' => null
                    ])
                ]);
            }

            // Vérifier l'étape 1
            $stepAdmi1 = StudentDetail::where('code_student', $codeStud)->first();
            // $step1 = DB::table("StudentDetails")->select('step')->where('code_student', $codeStud)->first();
            if (empty($stepAdmi1)) {
                return redirect('admission')->with([
                    'localstorage_data' => json_encode([
                        'current_step' => 'step1',
                        'code_student' => $codeStud
                    ])
                ]);
            }

            // Vérifier l'étape 2
            $stepAdmi2 = Medical::where('code_student', $codeStud)->first();
            if (empty($stepAdmi2)) {
                return redirect('admission')->with([
                    'localstorage_data' => json_encode([
                        'current_step' => 'step2',
                        'code_student' => $codeStud
                    ])
                ]);
            }

            // Vérifier l'étape 3 (Learning & Behavioural)
            $stepAdmi3 = LearningBehavioural::where('code_student', $codeStud)->first();
            if (empty($stepAdmi3)) {
                return redirect('admission')->with([
                    'localstorage_data' => json_encode([
                        'current_step' => 'step3',
                        'code_student' => $codeStud
                    ])
                ]);
            }

            // Vérifier l'étape 4 (Pickup)
            $stepAdmi4Pickup = ChildPickup::where('code_student', $codeStud)->first();
            if (empty($stepAdmi4Pickup)) {
                return redirect('admission')->with([
                    'localstorage_data' => json_encode([
                        'current_step' => 'step4',
                        'code_student' => $codeStud
                    ])
                ]);
            }

            // Vérifier l'étape 5 (Family) avec LEFT JOIN
            $stepAdmi4 = StudentDetail::query()
                ->leftJoin('parents as father', 'father.code_parent', '=', 'StudentDetails.code_father')
                ->leftJoin('parents as mother', 'mother.code_parent', '=', 'StudentDetails.code_mother')
                ->leftJoin('parents as guardian', 'guardian.code_parent', '=', 'StudentDetails.code_guardian')
                ->where('StudentDetails.code_student', $codeStud)
                ->first();

            if (
                empty($stepAdmi4) ||
                (empty($stepAdmi4->code_father) &&
                    empty($stepAdmi4->code_mother) &&
                    empty($stepAdmi4->code_guardian))
            ) {
                return redirect('admission')->with([
                    'localstorage_data' => json_encode([
                        'current_step' => 'step5',
                        'code_student' => $codeStud
                    ])
                ]);
            }

            // Toutes les étapes sont complètes
            return redirect('admission')->with([
                'localstorage_data' => json_encode([
                    'current_step' => 'step6',
                    'code_student' => $codeStud,
                    'completed' => true
                ])
            ]);
        } catch (\Exception $e) {
            return redirect('admission')->with([
                'localstorage_data' => json_encode([
                    'current_step' => 'step1',
                    'code_student' => null,
                    'error' => $e->getMessage()
                ])
            ]);
        }
    }

    /**
     * Duplicate an admission (only when status is process_end).
     * - PERSONAL INFORMATION (step1): empty — user must fill.
     * - MEDICAL / LEARNING & BEHAVIOURAL (step2): empty — user must fill.
     * - EMERGENCY AND AUTHORISE PERSONNE (step3/4): copied — pre-filled, user can modify.
     * - FAMILY INFORMATION (step5): copied — pre-filled, user can validate or modify.
     */
    public function duplicateAdmission(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $codeStudent = $request->code_student;
        if (empty($codeStudent)) {
            return redirect()->route('home-admission')->with('error', 'Missing admission reference.');
        }

        $userId = FacadesSession::get('user.id');
        $student = StudentDetail::where('code_student', $codeStudent)->where('id_userS', $userId)->first();
        if (!$student) {
            return redirect()->route('home-admission')->with('error', 'Admission not found or access denied.');
        }

        $academicYear = Academic_year::where('code', $student->code_academic)->first();
        if (!$academicYear || $academicYear->registration !== 'process_end') {
            return redirect()->route('home-admission')->with('error', 'Duplication is only allowed for admissions waiting for validation.');
        }

        $acaYear = AcademicYear::where('etat', 1)->first();
        if (!$acaYear) {
            return redirect()->route('home-admission')->with('error', 'No active academic year.');
        }

        $nowDateDb = date('Y-m-d');
        $nowTime = date('H:i:s');
        $newCodeStudent = 'STU' . time() . '_' . strtolower(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6));
        $newCodeAcademic = strtoupper(substr(str_shuffle('abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ0123456789'), 0, 8));
        $defaultNan = 'NAN';

        try {
            DB::beginTransaction();

            // --- FAMILY INFORMATION: keep links to existing parents, do not duplicate parent records ---
            $existingParentCode = function (?string $codeParent) use ($defaultNan): string {
                if (empty($codeParent) || strtoupper($codeParent) === $defaultNan) {
                    return $defaultNan;
                }

                return ParentModel::where('code_parent', $codeParent)->exists()
                    ? $codeParent
                    : $defaultNan;
            };

            $newCodeFather = $existingParentCode($student->code_father);
            $newCodeMother = $existingParentCode($student->code_mother);
            $newCodeGuardian = $existingParentCode($student->code_guardian);

            // --- PERSONAL INFORMATION: empty (only link to user and parents, no personal data) ---
            StudentDetail::create([
                'id_userS' => $student->id_userS,
                'sexe' => $defaultNan,
                'nom' => '',
                'prenom' => '',
                'birthday' => null,
                'birth_city' => '',
                'birth_country' => '',
                'nationality' => '',
                'first_lang' => '',
                'file' => null,
                'school' => $defaultNan,
                'id_type' => $defaultNan,
                'id_number' => $defaultNan,
                'home_adress' => $defaultNan,
                'mobile' => $defaultNan,
                'whatsapp' => $defaultNan,
                'email' => $defaultNan,
                'external_student' => 0,
                'code_student' => $newCodeStudent,
                'tb_father' => $defaultNan,
                'tb_mother' => $defaultNan,
                'tb_guardian' => $defaultNan,
                'code_father' => $newCodeFather,
                'code_mother' => $newCodeMother,
                'code_guardian' => $newCodeGuardian,
                'code_academic' => $newCodeAcademic,
                'date_enreg' => $nowDateDb,
                'heur_enreg' => $nowTime,
                'etat_stud' => 1,
            ]);

            
            // Academic_year: minimal record for new admission (no class/personal data until step1 filled)
            Academic_year::create([
                'id_user' => $userId,
                'nature' => 1,
                'academicyear' => $acaYear->year,
                'photo' => null,
                'last_name' => null,
                'first_name' => null,
                'codeFather' => $newCodeFather !== $defaultNan ? $newCodeFather : null,
                'codeMother' => $newCodeMother !== $defaultNan ? $newCodeMother : null,
                'codeGuardian' => $newCodeGuardian !== $defaultNan ? $newCodeGuardian : null,
                'class_current' => null,
                'id_class' => null,
                'level' => null,
                'sublevel' => null,
                'classroom' => null,
                'class_section' => null,
                'classroom_type' => null,
                'registration' => 'in_process',
                'code' => $newCodeAcademic,
                'invoice' => 'invoice_'.$newCodeAcademic.'_'.str_replace('-', '_', $acaYear->year),
                'registration_number' => null,
                'registration_num' => null,
                'admission_date' => $nowDateDb,
                'number' => null,
                'admission_number' => null,
                'number_admission' => null,
                'frais_scho' => 0,
                'statut' => null,
                'etat' => 1,
                'migration' => 0,
                'actif' => 1,
                'reason_of_leaving' => null,
                'more_reason_of_leaving' => null,
            ]);

            // Start at step1 so user fills Personal Information first
            AdmissionStep::create([
                'code_stud' => $newCodeStudent,
                'step' => 'step1',
                'academicyear' => $acaYear->year,
            ]);

            // --- MEDICAL / LEARNING & BEHAVIOURAL: not copied (empty) ---

            // --- EMERGENCY AND AUTHORISE PERSONNE: copied (pre-filled, user can modify) ---
            $emergency = Emergency::where('code_student', $codeStudent)->first();
            if ($emergency) {
                $em = $emergency->getAttributes();
                unset($em['id']);
                $em['code_student'] = $newCodeStudent;
                $em['code_academic'] = $newCodeAcademic;
                $em['date_enreg'] = $nowDateDb;
                $em['heur_enreg'] = $nowTime;
                Emergency::create($em);
            }

            $pickup = ChildPickup::where('code_student', $codeStudent)->first();
            if ($pickup) {
                $pa = $pickup->getAttributes();
                unset($pa['id']);
                $pa['code_student'] = $newCodeStudent;
                $pa['code_academic'] = $newCodeAcademic;
                $pa['date_enreg'] = $nowDateDb;
                $pa['heur_enreg'] = $nowTime;
                ChildPickup::create($pa);
            }

            // Commitment and File: not copied (user fills step1/step2 and accepts commitments again)

            DB::commit();

            return redirect()->to('admission?code_student=' . urlencode($newCodeStudent) . '&step=1')
                ->with('success', 'Admission duplicated. Personal and medical info are empty; emergency/pickup are pre-filled and family is linked to existing parent records. You can modify them as needed.');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Duplicate admission error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('home-admission')->with('error', 'Failed to duplicate admission. Please try again.');
        }
    }

    /**
     * Delete an admission that is still in process.
     */
    public function deleteAdmission(string $codeStudent)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $userId = FacadesSession::get('user.id');
        $student = StudentDetail::where('code_student', $codeStudent)
            ->where('id_userS', $userId)
            ->first();

        if (!$student) {
            return redirect()->route('home-admission')->with('error', 'Admission not found or access denied.');
        }

        $codeAcademic = $student->code_academic;
        if (empty($codeAcademic)) {
            return redirect()->route('home-admission')->with('error', 'Admission academic reference is missing.');
        }

        $academicYear = Academic_year::where('code', $codeAcademic)
            ->where('id_user', $userId)
            ->first();

        if (!$academicYear || $academicYear->registration !== 'in_process') {
            return redirect()->route('home-admission')->with('error', 'Only admissions in process can be deleted.');
        }

        try {
            DB::beginTransaction();

            FileModel::where('code_academic', $codeAcademic)->delete();
            Medical::where('code_academic', $codeAcademic)->delete();
            Emergency::where('code_academic', $codeAcademic)->delete();
            ChildPickup::where('code_academic', $codeAcademic)->delete();
            Commitment::where('code_academic', $codeAcademic)->delete();
            Document::where('code_academic', $codeAcademic)->delete();
            LearningBehavioural::where('code_academic', $codeAcademic)->delete();

            AdmissionStep::where('code_stud', $codeStudent)->delete();
            StudentDetail::where('code_academic', $codeAcademic)
                ->where('id_userS', $userId)
                ->delete();
            Academic_year::where('code', $codeAcademic)
                ->where('id_user', $userId)
                ->delete();

            DB::commit();

            return redirect()->route('home-admission')->with('success', 'Admission deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Delete admission error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('home-admission')->with('error', 'Failed to delete admission. Please try again.');
        }
    }

    public function insert_dashAdmin()
    {
        // Vérifier l'authentification
        $auth = $this->checkAuth();
        if ($auth) return $auth;

        $acaYear = AcademicYear::where('etat', 1)->first();
        $classe = Classe::where('aca_year', $acaYear->year)->get();

        $data = [
            'acayear' => $acaYear,
            'classes' => $classe,
        ];

        return redirect('admission')->with($data);
    }


    public function get_all_Admission(Request $request)
    {
        // Vérifier l'authentification
        $auth = $this->checkAuth();
        if ($auth) return $auth;

        $acaYear = AcademicYear::where('etat', 1)->first();
        $codeStudent = $request->code_student;
        $step1info = $codeStudent
            ? StudentDetail::query()
                ->leftJoin('files', 'files.code_student', '=', 'StudentDetails.code_student')
                ->leftJoin('academic_year', 'academic_year.code', '=', 'StudentDetails.code_academic')
                ->where('StudentDetails.code_student', $codeStudent)
                ->select('StudentDetails.*', 'files.filepassport', 'files.fileacademi', 'files.fileexams', 'files.filevacc', 'academic_year.class_current')
                ->first()
            : null;

        $step2info = new \stdClass;
        if ($codeStudent) {
            $medical = Medical::where('code_student', $codeStudent)->first();
            $emergency = Emergency::where('code_student', $codeStudent)->first();
            if ($medical) {
                foreach ($medical->toArray() as $k => $v) {
                    $step2info->$k = $v;
                }
            }
            if ($emergency) {
                foreach ($emergency->toArray() as $k => $v) {
                    $step2info->$k = $v;
                }
            }
        }

        $step3info = $codeStudent ? LearningBehavioural::where('code_student', $codeStudent)->first() : null;
        $step4info = $codeStudent ? ChildPickup::where('code_student', $codeStudent)->first() : null;

        $step4father = null;
        $step4mother = null;
        $step4guardian = null;
        if ($codeStudent) {
            $student = StudentDetail::where('code_student', $codeStudent)->first();
            if ($student) {
                if (!empty($student->code_father)) {
                    $step4father = ParentModel::where('code_parent', $student->code_father)->first();
                }
                if (!empty($student->code_mother)) {
                    $step4mother = ParentModel::where('code_parent', $student->code_mother)->first();
                }
                if (!empty($student->code_guardian)) {
                    $step4guardian = ParentModel::where('code_parent', $student->code_guardian)->first();
                }
            }
        }

        $step5info = $codeStudent ? Commitment::where('code_student', $codeStudent)->first() : null;

        $classe = Classe::with('subLevel.level')->where('aca_year', $acaYear->year)->get();
        $national = Nationality::all();
        $relation = Relationship::all();
        $fatherparent = ParentModel::where('person', 'Father')->get();
        $motherparent = ParentModel::where('person', 'Mother')->get();
        $parent = ParentModel::where('person', 'Guardian')->get();

        $requiredFilesByClass = $this->getRequiredFilesByClass($classe, $acaYear->year ?? '');
        $countries = Country::orderBy('nom_en_gb')->get();

        $data = [
            'acayear' => $acaYear,
            'national' => $national,
            'countries' => $countries,
            'relations' => $relation,
            'parents' => $parent,
            'motherparents' => $motherparent,
            'fatherparents' => $fatherparent,
            'step1info' => $step1info ?? (object) [],
            'step2info' => $step2info,
            'step3info' => $step3info,
            'step4info' => $step4info,
            'step4father' => $step4father,
            'step4mother' => $step4mother,
            'step4guardian' => $step4guardian,
            'step5info' => $step5info,
            'classes' => $classe,
            'requiredFilesByClass' => $requiredFilesByClass,
        ];

        return view('admissions.admission')->with($data);
    }

    /**
     * Allowed mimes and max size (KB) for step1 file uploads.
     */
    private const STEP1_FILE_MIMES = 'mimes:pdf,png,jpg,jpeg';
    private const STEP1_FILE_MAX_KB = 5120;

    /** FileLevel description_code => [request field name, files table column] */
    private const STEP1_FILE_MAP = [
        'PCP' => ['birth_certif', 'filepassport'],
        'VR' => ['updated_vacci', 'filevacc'],
        'Transcripts' => ['previous_academic', 'fileacademi'],
        'Transfer' => ['transfer_certif', 'fileexams'],
    ];

    /**
     * Required file uploads per class (from reqFiles by class level + academic year).
     * Returns [ 'PreN' => [ ['field_name'=>'...', 'files_column'=>'...', 'description'=>'...', 'description_code'=>'...'], ... ], ... ]
     */
    private function getRequiredFilesByClass($classes, string $acaYear): array
    {
        $byClass = [];
        foreach ($classes as $classe) {
            $levelId = $classe->subLevel?->id_level;
            if (!$levelId) {
                $byClass[$classe->name_classe] = [];
                continue;
            }
            $byClass[$classe->name_classe] = $this->getRequiredFilesForLevel($levelId, $acaYear);
        }
        return $byClass;
    }

    private function getRequiredFilesForLevel(int $levelId, string $acaYear): array
    {
        $reqFiles = ReqFile::where('id_level', $levelId)
            ->where(function ($q) use ($acaYear) {
                $q->where('academic_year', $acaYear)->orWhereNull('academic_year');
            })
            ->with('fileLevel')
            ->get();

        $list = [];
        foreach ($reqFiles as $rf) {
            $fl = $rf->fileLevel;
            if (!$fl || !isset(self::STEP1_FILE_MAP[$fl->description_code])) {
                continue;
            }
            [$fieldName, $filesColumn] = self::STEP1_FILE_MAP[$fl->description_code];
            $list[] = [
                'field_name' => $fieldName,
                'files_column' => $filesColumn,
                'description' => $fl->description,
                'description_code' => $fl->description_code,
            ];
        }

        return $list;
    }

    /**
     * Returns list of step1 file field names required for the given class (from reqFiles).
     */
    private function getRequiredFileFieldsForClass(string $classroomName, string $acaYear): array
    {
        $classe = Classe::with('subLevel.level')->where('name_classe', $classroomName)->where('aca_year', $acaYear)->first();
        $levelId = $classe?->subLevel?->id_level;
        if (!$levelId) {
            return [];
        }
        return collect($this->getRequiredFilesForLevel($levelId, $acaYear))
            ->pluck('field_name')
            ->all();
    }

    /**
     * Store one admission file in public folder: public/uploads/admission/{academic_year}/{code_student}/{name}.ext
     * Returns stored path (relative to public, e.g. uploads/admission/...) or null.
     */
    private function storeStep1File(Request $request, string $inputName, string $academicYear, string $codeStudent, string $fileLabel): ?string
    {
        if (!$request->hasFile($inputName) || !$request->file($inputName)->isValid()) {
            return null;
        }
        $file = $request->file($inputName);
        $ext = $file->getClientOriginalExtension() ?: $file->guessExtension();
        $safeName = $fileLabel . '_' . time() . '_' . substr(md5(uniqid()), 0, 8) . '.' . ($ext ?: 'pdf');
        $dir = 'uploads/admission/' . $academicYear . '/' . $codeStudent;
        $fullDir = public_path($dir);
        if (!file_exists($fullDir)) {
            mkdir($fullDir, 0755, true);
        }
        $file->move($fullDir, $safeName);
        return $dir . '/' . $safeName;
    }

    private function buildAdmissionNumberData(string $lastName, string $firstName, string $classroom, string $academicYear): array
    {
        $dernierNum = AcademicYear::getLastAdmissionNumber();
        $num = $dernierNum ? $this->incrementNumber(99999, $dernierNum->number) : '00001';
        $registrationNumber = $this->formatAdmissionRegistrationNumber($lastName, $firstName, $num, $classroom, $academicYear);

        return [
            'registration_number' => $registrationNumber,
            'admission_number' => $registrationNumber,
            'number' => (int)$num,
            'number_admission' => $num,
        ];
    }

    private function formatAdmissionRegistrationNumber(string $lastName, string $firstName, string $num, string $classroom, string $academicYear): string
    {
        $nomInitial = (!empty($lastName) && strlen($lastName) > 0) ? strtoupper($lastName[0]) : 'X';
        $prenomInitial = (!empty($firstName) && strlen($firstName) > 0) ? strtoupper($firstName[0]) : 'X';

        $class = Classe::getClassesByCodeAndYear($classroom, $academicYear);
        $CADM = $class->level;

        return 'IESA/' . date('Y') . $nomInitial . $prenomInitial . $num . '-' . $CADM;
    }

    /**
     * Save step1 data and files. Creates or updates StudentDetails, admission_step, files, academic_year.
     */
    public function PostInfo_Step1(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $existingStudent = StudentDetail::where('code_student', $request->code_student)->first();
        $isNewStudent = empty($existingStudent);

        $acaYear = AcademicYear::where('etat', 1)->first();
        $requiredFileFields = $this->getRequiredFileFieldsForClass($request->classroom, $acaYear?->year ?? '');

        $fileRuleOptional = ['nullable', 'file', self::STEP1_FILE_MIMES, 'max:' . self::STEP1_FILE_MAX_KB];
        $fileRuleRequired = [Rule::requiredIf($isNewStudent), 'nullable', 'file', self::STEP1_FILE_MIMES, 'max:' . self::STEP1_FILE_MAX_KB];

        $rules = [
            'id_us' => 'required',
            'code_student' => 'required|string|max:64',
            'gender' => 'required|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'birth_city' => 'required|string|max:255',
            'birth_country' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'classroom' => 'required|string|max:100',
            'first_lang' => 'required|string|max:100',
            'photo' => $fileRuleRequired,
            'birth_certif' => in_array('birth_certif', $requiredFileFields) ? $fileRuleRequired : $fileRuleOptional,
            'previous_academic' => in_array('previous_academic', $requiredFileFields) ? $fileRuleRequired : $fileRuleOptional,
            'transfer_certif' => in_array('transfer_certif', $requiredFileFields) ? $fileRuleRequired : $fileRuleOptional,
            'updated_vacci' => in_array('updated_vacci', $requiredFileFields) ? $fileRuleRequired : $fileRuleOptional,
        ];

        $messages = [
            'required' => 'The :attribute field is required.',
            'date' => 'The :attribute must be a valid date.',
            'max' => 'The :attribute must not be greater than :max kilobytes.',
            'mimes' => 'The :attribute must be a file of type: pdf, png, jpg, jpeg.',
            'file' => 'The :attribute must be a valid file.',
            'photo.mimes' => 'ID photo must be pdf, png, jpg or jpeg (max 5MB).',
            'photo.max' => 'ID photo must not exceed 5MB.',
            'birth_certif.mimes' => 'Birth certificate/Passport must be pdf, png, jpg or jpeg (max 5MB).',
            'birth_certif.max' => 'Birth certificate/Passport must not exceed 5MB.',
            'previous_academic.mimes' => 'Previous Academic Reports must be pdf, png, jpg or jpeg (max 5MB).',
            'previous_academic.max' => 'Previous Academic Reports must not exceed 5MB.',
            'transfer_certif.mimes' => 'Transfer Certificate/Exams Results must be pdf, png, jpg or jpeg (max 5MB).',
            'transfer_certif.max' => 'Transfer Certificate/Exams Results must not exceed 5MB.',
            'updated_vacci.mimes' => 'Updated Vaccination Records must be pdf, png, jpg or jpeg (max 5MB).',
            'updated_vacci.max' => 'Updated Vaccination Records must not exceed 5MB.',
        ];
        $attributes = [
            'id_us' => 'User',
            'code_student' => 'Student code',
            'gender' => 'Gender',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'birthday' => 'Birthday',
            'birth_city' => 'Birth city',
            'birth_country' => 'Birth country',
            'nationality' => 'Nationality',
            'classroom' => 'Classroom',
            'first_lang' => 'First language',
            'photo' => 'ID photo',
            'birth_certif' => 'Birth certificate/Passport',
            'previous_academic' => 'Previous Academic Reports',
            'transfer_certif' => 'Transfer Certificate/Exams Results',
            'updated_vacci' => 'Updated Vaccination Records',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $detail = collect($errors)->map(function ($messages, $field) use ($attributes) {
                $label = $attributes[$field] ?? $field;
                return $label . ': ' . implode(' ', $messages);
            })->implode("\n• ");
            return response()->json([
                'success' => false,
                'errors' => $errors,
                'message' => 'Validation failed. Please check the following:',
                'message_detail' => $detail ? '• ' . $detail : 'Please check the fields and allowed file formats (pdf, png, jpg, jpeg, max 5MB).',
            ], 422);
        }

        $acaYear = AcademicYear::where('etat', 1)->first();
        if (!$acaYear) {
            return response()->json(['success' => false, 'message' => 'No active academic year found.'], 500);
        }

        $academicYearSlug = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $acaYear->year);
        $codeStudent = $request->code_student;
        $nowDate = date('d-m-Y');
        $nowDateDb = date('Y-m-d'); // format for DB date columns (MySQL)
        $nowTime = date('H:i:s');
        $defaultNan = 'NAN';

        if (!empty($existingStudent) && $existingStudent->id_userS != $request->id_us) {
            return response()->json(['success' => false, 'message' => 'You are not allowed to modify this admission.'], 403);
        }

        $dedupeLock = Cache::lock('admission-step1:' . sha1(implode('|', [
            $request->id_us,
            $acaYear->year,
            strtolower(trim($request->first_name)),
            strtolower(trim($request->last_name)),
            $request->birthday,
        ])), 15);

        try {
            $dedupeLock->block(10);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'This admission is already being saved. Please try again in a few seconds.',
            ], 429);
        }

        if (empty($existingStudent)) {
            $sameChildAdmission = $this->findExistingChildAdmission(
                $request->id_us,
                $acaYear->year,
                $request->first_name,
                $request->last_name,
                $request->birthday
            );

            if ($sameChildAdmission) {
                $sameChildAcademicYear = Academic_year::where('code', $sameChildAdmission->code_academic)
                    ->where('id_user', $request->id_us)
                    ->first();

                if ($sameChildAcademicYear && $sameChildAcademicYear->registration !== 'in_process') {
                    $dedupeLock->release();
                    return response()->json([
                        'success' => false,
                        'message' => 'An admission already exists for this child in the current academic year.',
                        'message_detail' => 'Please continue from the admission list instead of creating a new admission.',
                        'code_student' => $sameChildAdmission->code_student,
                    ], 409);
                }

                $existingStudent = $sameChildAdmission;
                $codeStudent = $sameChildAdmission->code_student;
            }
        }

        if (empty($existingStudent)) {
            try {
                DB::beginTransaction();

                $codeacademic = strtoupper(substr(str_shuffle('abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ0123456789'), 0, 8));

                $photoPath = $this->storeStep1File($request, 'photo', $academicYearSlug, $codeStudent, 'photo');
                $birth_certif = $this->storeStep1File($request, 'birth_certif', $academicYearSlug, $codeStudent, 'birth_certif');
                $previous_academic = $this->storeStep1File($request, 'previous_academic', $academicYearSlug, $codeStudent, 'previous_academic');
                $transfer_certif = $this->storeStep1File($request, 'transfer_certif', $academicYearSlug, $codeStudent, 'transfer_certif');
                $updated_vacci = $this->storeStep1File($request, 'updated_vacci', $academicYearSlug, $codeStudent, 'updated_vacci');

                StudentDetail::create([
                    'id_userS' => $request->id_us,
                    'sexe' => $request->gender,
                    'prenom' => $request->first_name,
                    'nom' => $request->last_name,
                    'birthday' => $request->birthday,
                    'birth_city' => $request->birth_city,
                    'birth_country' => $request->birth_country,
                    'nationality' => $request->nationality,
                    'first_lang' => $request->first_lang,
                    'file' => $photoPath,
                    'date_enreg' => $nowDateDb,
                    'heur_enreg' => $nowTime,
                    'etat_stud' => 1,
                    'school' => $defaultNan,
                    'id_type' => $defaultNan,
                    'id_number' => $defaultNan,
                    'home_adress' => $defaultNan,
                    'mobile' => $defaultNan,
                    'whatsapp' => $defaultNan,
                    'email' => $defaultNan,
                    'external_student' => 0,
                    'code_student' => $codeStudent,
                    'tb_father' => $defaultNan,
                    'tb_mother' => $defaultNan,
                    'tb_guardian' => $defaultNan,
                    'code_father' => $defaultNan,
                    'code_mother' => $defaultNan,
                    'code_guardian' => $defaultNan,
                    'code_academic' => $codeacademic,
                ]);

                AdmissionStep::updateOrCreate(
                    ['code_stud' => $codeStudent],
                    ['step' => 'step2', 'academicyear' => $acaYear->year]
                );

                FileModel::create([
                    'filepassport' => $birth_certif,
                    'fileacademi' => $previous_academic,
                    'fileexams' => $transfer_certif,
                    'filevacc' => $updated_vacci,
                    'code_student' => $codeStudent,
                    'code_academic' => $codeacademic,
                    'date_enreg' => $nowDateDb,
                    'heur_enreg' => $nowTime,
                    'etat' => 1,
                ]);

                $selectedClass = Classe::with('subLevel.level')->where('name_classe', $request->classroom)->where('aca_year', $acaYear->year)->first();
                
                $academicYear = str_replace('-', '_', $acaYear->year);
                $invoice = 'invoice_'.$codeacademic.'_'.$academicYear;
                $admissionNumberData = $this->buildAdmissionNumberData(
                    $request->last_name,
                    $request->first_name,
                    $request->classroom,
                    $acaYear->year
                );
                Academic_year::create([
                    'id_user' => $request->id_us,
                    'nature' => 1,
                    'academicyear' => $acaYear->year,
                    'photo' => null,
                    'last_name' => $request->last_name,
                    'first_name' => $request->first_name,
                    'codeFather' => null,
                    'codeMother' => null,
                    'codeGuardian' => null,
                    'class_current' => $request->classroom,
                    'id_class' => $selectedClass?->id,
                    'level' => $selectedClass?->subLevel?->level?->level_name,
                    'sublevel' => $selectedClass?->subLevel?->description,
                    'classroom' => $request->classroom,
                    'class_section' => null,
                    'classroom_type' => null,
                    'registration' => 'in_process',
                    'code' => $codeacademic,
                    'invoice' => $invoice,
                    'registration_number' => $admissionNumberData['registration_number'],
                    'registration_num' => null,
                    'admission_date' => $nowDateDb,
                    'number' => $admissionNumberData['number'],
                    'admission_number' => $admissionNumberData['admission_number'],
                    'number_admission' => $admissionNumberData['number_admission'],
                    'frais_scho' => 0,
                    'statut' => null,
                    'etat' => 1,
                    'migration' => 0,
                    'actif' => 1,
                    'reason_of_leaving' => null,
                    'more_reason_of_leaving' => null,
                ]);

                DB::commit();

                $dedupeLock->release();
                return response()->json([
                    'success' => true,
                    'message' => 'Step 1 saved successfully.',
                    'code_student' => $codeStudent,
                    'code_academic' => $codeacademic,
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                $dedupeLock->release();
                Log::error('Admission PostInfo_Step1 create error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                $messageDetail = config('app.debug') ? $e->getMessage() : null;
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while saving. Please try again.',
                    'message_detail' => $messageDetail,
                ], 500);
            }
        }

        try {
            $student = StudentDetail::where('code_student', $codeStudent)
                ->where('id_userS', $request->id_us)
                ->first();
            if (!$student) {
                $dedupeLock->release();
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found or you are not allowed to modify this admission.',
                ], 403);
            }

            DB::beginTransaction();

            $codeacademic = $student->code_academic ?? 'NAN';

            $photoPath = $this->storeStep1File($request, 'photo', $academicYearSlug, $codeStudent, 'photo');

            $studentUpdate = [
                'sexe' => $request->gender,
                'prenom' => $request->first_name,
                'nom' => $request->last_name,
                'birthday' => $request->birthday,
                'birth_city' => $request->birth_city,
                'birth_country' => $request->birth_country,
                'nationality' => $request->nationality,
                'first_lang' => $request->first_lang,
                'date_enreg' => $nowDateDb,
                'heur_enreg' => $nowTime,
                'etat_stud' => 1,
            ];
            if ($photoPath !== null) {
                $studentUpdate['file'] = $photoPath;
            }
            StudentDetail::where('code_student', $codeStudent)
                ->where('id_userS', $request->id_us)
                ->update($studentUpdate);

            AdmissionStep::updateOrCreate(
                ['code_stud' => $codeStudent],
                ['step' => 'step2', 'academicyear' => $acaYear->year]
            );

            $fileUpdate = ['date_enreg' => $nowDateDb, 'heur_enreg' => $nowTime, 'etat' => 1];
            $birth_certif = $this->storeStep1File($request, 'birth_certif', $academicYearSlug, $codeStudent, 'birth_certif');
            if ($birth_certif !== null) {
                $fileUpdate['filepassport'] = $birth_certif;
            }
            $previous_academic = $this->storeStep1File($request, 'previous_academic', $academicYearSlug, $codeStudent, 'previous_academic');
            if ($previous_academic !== null) {
                $fileUpdate['fileacademi'] = $previous_academic;
            }
            $transfer_certif = $this->storeStep1File($request, 'transfer_certif', $academicYearSlug, $codeStudent, 'transfer_certif');
            if ($transfer_certif !== null) {
                $fileUpdate['fileexams'] = $transfer_certif;
            }
            $updated_vacci = $this->storeStep1File($request, 'updated_vacci', $academicYearSlug, $codeStudent, 'updated_vacci');
            if ($updated_vacci !== null) {
                $fileUpdate['filevacc'] = $updated_vacci;
            }

            $filesRecord = FileModel::where('code_student', $codeStudent)->first();
            if ($filesRecord) {
                FileModel::where('code_student', $codeStudent)->update($fileUpdate);
            } else {
                FileModel::create(array_merge($fileUpdate, [
                    'filepassport' => $birth_certif,
                    'fileacademi' => $previous_academic,
                    'fileexams' => $transfer_certif,
                    'filevacc' => $updated_vacci,
                    'code_student' => $codeStudent,
                    'code_academic' => $codeacademic,
                ]));
            }

            $selectedClass = Classe::with('subLevel.level')->where('name_classe', $request->classroom)->where('aca_year', $acaYear->year)->first();

            $academicYearRecord = Academic_year::where('code', $codeacademic)->first();
            $academicYearUpdate = [
                    'id_user' => $request->id_us,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'class_current' => $request->classroom,
                    'classroom' => $request->classroom,
                    'id_class' => $selectedClass?->id,
                    'level' => $selectedClass?->subLevel?->level?->level_name,
                    'sublevel' => $selectedClass?->subLevel?->description,
                    'academicyear' => $acaYear->year,
                    'admission_date' => $nowDateDb,
                    'etat' => 1,
                    'registration' => 'in_process',
                ];

            if ($academicYearRecord && empty($academicYearRecord->number)) {
                $academicYearUpdate = array_merge($academicYearUpdate, $this->buildAdmissionNumberData(
                    $request->last_name,
                    $request->first_name,
                    $request->classroom,
                    $acaYear->year
                ));
            }

            Academic_year::where('code', $codeacademic)->update($academicYearUpdate);

            DB::commit();

            $dedupeLock->release();
            return response()->json([
                'success' => true,
                'message' => 'Step 1 updated successfully.',
                'code_student' => $codeStudent,
                'code_academic' => $codeacademic,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            $dedupeLock->release();
            Log::error('Admission PostInfo_Step1 update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $messageDetail = config('app.debug') ? $e->getMessage() : null;
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating. Please try again.',
                'message_detail' => $messageDetail,
            ], 500);
        }
    }


    public function PostInfo_Step2(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $rules = [
            'code_student' => 'required|string|max:64',
            'blood_group' => 'required|string|max:50',
            'doctor_name' => 'nullable|string|max:255',
            'doctor_contact' => 'nullable|string|max:50',
            'any_recom' => 'required|string|in:no,yes',
            'any_medecine' => 'nullable|string|in:no,yes',
            'any_medic' => 'nullable|string|in:no,yes',
            'any_allergy' => 'nullable|string|in:no,yes',
            'medical_justification' => 'nullable|file|' . self::STEP1_FILE_MIMES . '|max:' . self::STEP1_FILE_MAX_KB,
            'learning_justification' => 'nullable|file|' . self::STEP1_FILE_MIMES . '|max:' . self::STEP1_FILE_MAX_KB,
            'has_condition' => 'required|string|in:yes,no',
        ];

        $attributes = [
            'code_student' => 'Student code',
            'blood_group' => 'Blood group',
            'doctor_name' => 'Doctor name',
            'doctor_contact' => 'Doctor contact',
            'any_recom' => 'Any recommendation',
            'has_condition' => 'Learning/behavioural condition',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'required' => 'The :attribute field is required.',
            'in' => 'The :attribute field is invalid.',
        ], $attributes);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $detail = collect($errors)->map(function ($messages, $field) use ($attributes) {
                $label = $attributes[$field] ?? $field;
                return $label . ': ' . implode(' ', $messages);
            })->implode("\n• ");
            return response()->json([
                'success' => false,
                'errors' => $errors,
                'message' => 'Validation failed. Please check the following:',
                'message_detail' => $detail ? '• ' . $detail : 'Please check the required fields.',
            ], 422);
        }

        $forbidden = $this->ensureStudentOwnership($request);
        if ($forbidden) {
            return $forbidden;
        }
        $student = $this->getStudentForUser($request->code_student, $request->input('id_us') ?? FacadesSession::get('user.id'));
        $acaYear = AcademicYear::where('etat', 1)->first();
        if (!$acaYear) {
            return response()->json(['success' => false, 'message' => 'No active academic year found.'], 500);
        }

        $academicYearSlug = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $acaYear->year);
        $codeStudent = $request->code_student;
        $codeAcademic = $student->code_academic ?? 'NAN';
        $nowDate = date('d-m-Y');
        $nowTime = date('H:i:s');

        try {
            DB::beginTransaction();

            $mediJust = $this->storeStep1File($request, 'medical_justification', $academicYearSlug, $codeStudent, 'medical_justification');
            $learJust = $this->storeStep1File($request, 'learning_justification', $academicYearSlug, $codeStudent, 'learning_justification');

            $existingMedical = Medical::where('code_student', $codeStudent)->first();
            $medicalData = [
                'code_academic' => $codeAcademic,
                'blood_group' => $request->blood_group,
                'doctor_name' => $request->doctor_name,
                'doctor_contact' => $request->doctor_contact,
                'any_recommandation' => $request->any_recom,
                'medecine_health' => $request->any_medecine ?? 'no',
                'medication_school' => $request->any_medic ?? 'no',
                'medication_list' => $request->medication_list,
                'medication1' => $request->Drug1,
                'medication2' => $request->Drug2,
                'medication3' => $request->Drug3,
                'medication4' => $request->Drug4,
                'medication5' => $request->Drug5,
                'medication6' => $request->Drug6,
                'medication7' => $request->Drug7,
                'medication8' => $request->Drug8,
                'allergy' => $request->any_allergy ?? 'no',
                'allergy_reaction' => $request->allergy_reaction,
                'allergy_food' => $request->allergy_food ?? '',
                'allergy_insect' => $request->allergy_insect ?? '',
                'allergy_medicine' => $request->allergy_medicine ?? '',
                'allergy_other' => $request->allergy_other ?? '',
                'allergy_resp_required' => $request->allergy_resp_required ?? '',
                'other_medication_infos' => $request->other_medical_info,
                'has_other_conditions' => $request->has_other_conditions ?? 'no',
                'learning_difficulty' => $request->learning_difficulties,
                'date_enreg' => $nowDate,
                'heur_enreg' => $nowTime,
                'etat' => 1,
            ];
            $medicalData['other_med_info_file'] = $mediJust ?? $existingMedical?->other_med_info_file;
            $medicalData['learning_diff_file'] = $learJust ?? $existingMedical?->learning_diff_file;

            Medical::updateOrCreate(
                ['code_student' => $codeStudent],
                array_merge($medicalData, ['code_student' => $codeStudent])
            );

            $learningData = [
                'code_academic' => $codeAcademic,
                'has_condition' => $request->has_condition,
                'learning_dyslexia' => $request->boolean('learning_dyslexia'),
                'learning_dyscalculia' => $request->boolean('learning_dyscalculia'),
                'learning_add_adhd' => $request->boolean('learning_add_adhd'),
                'learning_autism_spectrum' => $request->boolean('learning_autism_spectrum'),
                'learning_speech_language' => $request->boolean('learning_speech_language'),
                'learning_global_delay' => $request->boolean('learning_global_delay'),
                'learning_other_specify' => $request->learning_other_specify,
                'behaviour_group_setting' => $request->boolean('behaviour_group_setting'),
                'behaviour_aggressive' => $request->boolean('behaviour_aggressive'),
                'behaviour_impulsivity' => $request->boolean('behaviour_impulsivity'),
                'behaviour_emotional_social' => $request->boolean('behaviour_emotional_social'),
                'behaviour_sensory' => $request->boolean('behaviour_sensory'),
                'behaviour_toileting' => $request->boolean('behaviour_toileting'),
                'behaviour_other_specify' => $request->behaviour_other_specify,
                'other_information' => $request->other_information,
                'date_enreg' => $nowDate,
                'heur_enreg' => $nowTime,
                'etat' => 1,
            ];

            LearningBehavioural::updateOrCreate(
                ['code_student' => $codeStudent],
                array_merge($learningData, ['code_student' => $codeStudent])
            );

            AdmissionStep::where('code_stud', $codeStudent)->update([
                'step' => 'step4',
                'academicyear' => $acaYear->year,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Step 2 (Medical & Learning Behavioural) saved successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Admission PostInfo_Step2 error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving. Please try again.',
            ], 500);
        }
    }


    public function PostInfo_Step3(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $rules = [
            'code_student' => 'required|string|max:64',
            'has_condition' => 'required|string|in:yes,no',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'required' => 'The :attribute field is required.',
            'in' => 'The :attribute field is invalid.',
        ], ['code_student' => 'Student code', 'has_condition' => 'Learning/behavioural condition']);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $detail = collect($errors)->map(fn ($msgs, $field) => ucfirst(str_replace('_', ' ', $field)) . ': ' . implode(' ', $msgs))->implode("\n• ");
            return response()->json([
                'success' => false,
                'errors' => $errors,
                'message' => 'Validation failed.',
                'message_detail' => $detail ? '• ' . $detail : 'Please check the required fields.',
            ], 422);
        }

        $forbidden = $this->ensureStudentOwnership($request);
        if ($forbidden) {
            return $forbidden;
        }
        $student = $this->getStudentForUser($request->code_student, $request->input('id_us') ?? FacadesSession::get('user.id'));
        $acaYear = AcademicYear::where('etat', 1)->first();
        if (!$acaYear) {
            return response()->json(['success' => false, 'message' => 'No active academic year found.'], 500);
        }

        $codeStudent = $request->code_student;
        $codeAcademic = $student->code_academic ?? 'NAN';
        $nowDate = date('d-m-Y');
        $nowTime = date('H:i:s');

        try {
            DB::beginTransaction();

            $data = [
                'code_academic' => $codeAcademic,
                'has_condition' => $request->has_condition,
                'learning_dyslexia' => $request->boolean('learning_dyslexia'),
                'learning_dyscalculia' => $request->boolean('learning_dyscalculia'),
                'learning_add_adhd' => $request->boolean('learning_add_adhd'),
                'learning_autism_spectrum' => $request->boolean('learning_autism_spectrum'),
                'learning_speech_language' => $request->boolean('learning_speech_language'),
                'learning_global_delay' => $request->boolean('learning_global_delay'),
                'learning_other_specify' => $request->learning_other_specify,
                'behaviour_group_setting' => $request->boolean('behaviour_group_setting'),
                'behaviour_aggressive' => $request->boolean('behaviour_aggressive'),
                'behaviour_impulsivity' => $request->boolean('behaviour_impulsivity'),
                'behaviour_emotional_social' => $request->boolean('behaviour_emotional_social'),
                'behaviour_sensory' => $request->boolean('behaviour_sensory'),
                'behaviour_toileting' => $request->boolean('behaviour_toileting'),
                'behaviour_other_specify' => $request->behaviour_other_specify,
                'other_information' => $request->other_information,
                'date_enreg' => $nowDate,
                'heur_enreg' => $nowTime,
                'etat' => 1,
            ];

            LearningBehavioural::updateOrCreate(
                ['code_student' => $codeStudent],
                array_merge($data, ['code_student' => $codeStudent])
            );

            AdmissionStep::where('code_stud', $codeStudent)->update([
                'step' => 'step4',
                'academicyear' => $acaYear->year,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Step 3 saved successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Admission PostInfo_Step3 (LearningBehavioural) error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving. Please try again.',
            ], 500);
        }
    }


    public function PostInfo_Step4(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $rules = [
            'code_student' => 'required|string|max:64',
            'emergency_name1' => 'required|string|max:255',
            'emergency_contact1' => 'required|string|max:50',
            'emergency_relation1' => 'required|string|max:255',
            'emergency_name2' => 'nullable|string|max:255',
            'emergency_contact2' => 'nullable|string|max:50',
            'emergency_relation2' => 'nullable|string|max:255',
            'pickup_name1' => 'required|string|max:255',
            'pickup_contact1' => 'required|string|max:50',
            'pickup_relation1' => 'required|string|max:255',
            'pickup_name2' => 'nullable|string|max:255',
            'pickup_contact2' => 'nullable|string|max:50',
            'pickup_relation2' => 'nullable|string|max:255',
            'pickup_name3' => 'nullable|string|max:255',
            'pickup_contact3' => 'nullable|string|max:50',
            'pickup_relation3' => 'nullable|string|max:255',
            'pickup_photo1' => 'nullable|file|' . self::STEP1_FILE_MIMES . '|max:' . self::STEP1_FILE_MAX_KB,
            'pickup_photo2' => 'nullable|file|' . self::STEP1_FILE_MIMES . '|max:' . self::STEP1_FILE_MAX_KB,
            'pickup_photo3' => 'nullable|file|' . self::STEP1_FILE_MIMES . '|max:' . self::STEP1_FILE_MAX_KB,
        ];

        $attributes = [
            'code_student' => 'Student code',
            'emergency_name1' => 'Emergency name 1',
            'emergency_contact1' => 'Emergency contact 1',
            'emergency_relation1' => 'Emergency relation 1',
            'emergency_name2' => 'Emergency name 2',
            'emergency_contact2' => 'Emergency contact 2',
            'emergency_relation2' => 'Emergency relation 2',
            'pickup_name1' => 'Pickup name 1',
            'pickup_contact1' => 'Pickup contact 1',
            'pickup_relation1' => 'Pickup relation 1',
            'pickup_name2' => 'Pickup name 2',
            'pickup_contact2' => 'Pickup contact 2',
            'pickup_relation2' => 'Pickup relation 2',
            'pickup_name3' => 'Pickup name 3',
            'pickup_contact3' => 'Pickup contact 3',
            'pickup_relation3' => 'Pickup relation 3',
            'pickup_photo1' => 'Pickup photo 1',
            'pickup_photo2' => 'Pickup photo 2',
            'pickup_photo3' => 'Pickup photo 3',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'required' => 'The :attribute field is required.',
        ], $attributes);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $detail = collect($errors)->map(function ($messages, $field) use ($attributes) {
                $label = $attributes[$field] ?? $field;
                return $label . ': ' . implode(' ', $messages);
            })->implode("\n• ");
            return response()->json([
                'success' => false,
                'errors' => $errors,
                'message' => 'Validation failed. Please check the following:',
                'message_detail' => $detail ? '• ' . $detail : 'Please check the required fields.',
            ], 422);
        }

        $forbidden = $this->ensureStudentOwnership($request);
        if ($forbidden) {
            return $forbidden;
        }
        $student = $this->getStudentForUser($request->code_student, $request->input('id_us') ?? FacadesSession::get('user.id'));
        $acaYear = AcademicYear::where('etat', 1)->first();
        if (!$acaYear) {
            return response()->json(['success' => false, 'message' => 'No active academic year found.'], 500);
        }

        $academicYearSlug = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $acaYear->year);
        $codeStudent = $request->code_student;
        $codeAcademic = $student->code_academic ?? 'NAN';
        $nowDate = date('d-m-Y');
        $nowTime = date('H:i:s');

        try {
            DB::beginTransaction();

            $pick1 = $this->storeStep1File($request, 'pickup_photo1', $academicYearSlug, $codeStudent, 'pickup_photo1');
            $pick2 = $this->storeStep1File($request, 'pickup_photo2', $academicYearSlug, $codeStudent, 'pickup_photo2');
            $pick3 = $this->storeStep1File($request, 'pickup_photo3', $academicYearSlug, $codeStudent, 'pickup_photo3');

            $existing = ChildPickup::where('code_student', $codeStudent)->first();
            $pickupData = [
                'id_userC' => $request->id_us ?? $student->id_userS ?? 0,
                'code_academic' => $codeAcademic,
                'pickup_name1' => $request->pickup_name1,
                'pickup_contact1' => $request->pickup_contact1,
                'pickup_relation1' => $request->pickup_relation1,
                'pickup_name2' => $request->pickup_name2,
                'pickup_contact2' => $request->pickup_contact2,
                'pickup_relation2' => $request->pickup_relation2,
                'pickup_name3' => $request->pickup_name3,
                'pickup_contact3' => $request->pickup_contact3,
                'pickup_relation3' => $request->pickup_relation3,
                'date_enreg' => $nowDate,
                'heur_enreg' => $nowTime,
                'etat' => 1,
            ];
            $pickupData['filepickup1'] = $pick1 ?? $existing?->filepickup1;
            $pickupData['filepickup2'] = $pick2 ?? $existing?->filepickup2;
            $pickupData['filepickup3'] = $pick3 ?? $existing?->filepickup3;

            ChildPickup::updateOrCreate(
                ['code_student' => $codeStudent],
                array_merge($pickupData, ['code_student' => $codeStudent])
            );

            $studentId = $student->id_stud ?? $student->id ?? 0;
            Emergency::updateOrCreate(
                ['code_student' => $codeStudent],
                [
                    'id_userE' => $studentId,
                    'code_academic' => $codeAcademic,
                    'emergency_name1' => $request->emergency_name1,
                    'emergency_contact1' => $request->emergency_contact1,
                    'emergency_relation1' => $request->emergency_relation1,
                    'emergency_name2' => $request->emergency_name2,
                    'emergency_contact2' => $request->emergency_contact2,
                    'emergency_relation2' => $request->emergency_relation2,
                    'date_enreg' => $nowDate,
                    'heur_enreg' => $nowTime,
                    'etat' => 1,
                ]
            );

            AdmissionStep::where('code_stud', $codeStudent)->update([
                'step' => 'step5',
                'academicyear' => $acaYear->year,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Step 3 (Pickup & Emergency contacts) saved successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Admission PostInfo_Step4 error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving. Please try again.',
            ], 500);
        }
    }


    public function PostInfo_Step5(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        if ($request->has('responsible')) {
            $request->merge([
                'responsible' => strtolower((string) $request->responsible) === 'yes' ? 'Yes' : 'No',
            ]);
        }

        $rules = [
            'code_student' => 'required|string|max:64',
            'person' => 'required|string|in:Father,Mother,Guardian',
            'civility' => 'required|string|max:50',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            // 'phone' => 'required|string|max:50',
            'home_phone' => 'nullable|string|max:50',
            // 'personal_phone' => 'required|string|max:50',
            'work_phone' => 'nullable|string|max:50',
            // 'other_phone' => 'required|string|max:50',
            'whatsapp_phone' => 'required|string|max:50',
            'email_1' => 'required|email',
            'email_2' => 'nullable|email',
            'main_mobile' => 'required|string|max:50',
            'other_mobile' => 'nullable|string|max:50',
            'adress' => 'required|string|max:500',
            'postal_code' => 'nullable|string|max:20',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'main_language' => 'required|string|max:50',
            'occupation' => 'required|string|max:255',
            'enterprise' => 'nullable|string|max:255',
            'enterprise_addres' => 'nullable|string|max:500',
            'responsible' => 'required|string|in:Yes,No',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'required' => 'The :attribute field is required.',
            'in' => 'The :attribute field is invalid.',
        ], array_combine(array_keys($rules), array_map(fn ($k) => str_replace('_', ' ', ucfirst($k)), array_keys($rules))));

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $detail = collect($errors)->map(fn ($msgs, $field) => ucfirst(str_replace('_', ' ', $field)) . ': ' . implode(' ', $msgs))->implode("\n• ");
            return response()->json([
                'success' => false,
                'errors' => $errors,
                'message' => 'Validation failed. Please check the following:',
                'message_detail' => $detail ? '• ' . $detail : 'Please check the required fields.',
            ], 422);
        }

        $forbidden = $this->ensureStudentOwnership($request);
        if ($forbidden) {
            return $forbidden;
        }
        $student = $this->getStudentForUser($request->code_student, $request->input('id_us') ?? FacadesSession::get('user.id'));

        try {
            DB::beginTransaction();
            $codeParent = strtoupper(substr(str_shuffle('abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ0123456789'), 0, 8));

            ParentModel::create([
                'id_user' => $request->id_us ?? $student->id_userS,
                'person' => $request->person,
                'civility' => $request->civility,
                'last_name' => $request->last_name,
                'fist_name' => $request->first_name,
                // 'phone' => $request->phone,
                'home_phone' => $request->home_phone,
                // 'personnal_phone' => $request->personal_phone,
                'work_phone' => $request->work_phone,
                // 'other_phone' => $request->other_phone,
                'whatsapp_phone' => $request->whatsapp_phone,
                'email' => $request->email_1,
                'email2' => $request->email_2,
                'main_mobile' => $request->main_mobile,
                'other_mobile' => $request->other_mobile,
                'adress' => $request->adress,
                'postal_code' => $request->postal_code,
                'city' => $request->city,
                'country' => $request->country,
                'nationality' => $request->nationality,
                'main_language' => $request->main_language,
                'occupation' => $request->occupation,
                'enterprise' => $request->enterprise,
                'enterprise_adress' => $request->enterprise_addres,
                'responsible_of_school_fees' => $request->responsible,
                'code_parent' => $codeParent,
                'code_student' => $request->code_student,
                'update_infos' => '0',
            ]);

            $updateStudent = ['code_father' => $student->code_father, 'code_mother' => $student->code_mother, 'code_guardian' => $student->code_guardian];
            if ($request->person === 'Father') {
                $updateStudent['code_father'] = $codeParent;
            }
            if ($request->person === 'Mother') {
                $updateStudent['code_mother'] = $codeParent;
            }
            if ($request->person === 'Guardian') {
                $updateStudent['code_guardian'] = $codeParent;
            }
            StudentDetail::where('code_student', $request->code_student)->update($updateStudent);

            $rayUpdate = ['codeFather' => $student->code_father, 'codeMother' => $student->code_mother, 'codeGuardian' => $student->code_guardian];
            if ($request->person === 'Father') {
                $rayUpdate['codeFather'] = $codeParent;
            }
            if ($request->person === 'Mother') {
                $rayUpdate['codeMother'] = $codeParent;
            }
            if ($request->person === 'Guardian') {
                $rayUpdate['codeGuardian'] = $codeParent;
            }
            Academic_year::where('code', $student->code_academic)->update($rayUpdate);

            AdmissionStep::where('code_stud', $request->code_student)->update([
                'step' => 'step5',
                'academicyear' => AcademicYear::where('etat', 1)->first()?->year ?? date('Y'),
            ]);

            DB::commit();

            $name = trim($request->first_name . ' ' . $request->last_name);
            $redirectUrl = url('admission') . '?code_student=' . urlencode($request->code_student) . '&step=step4';
            return response()->json([
                'success' => true,
                'message' => 'Parent saved successfully.',
                'parentName' => $name,
                'person' => $request->person,
                'redirect' => $redirectUrl,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Admission PostInfo_Step5 error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving. Please try again.',
            ], 500);
        }
    }


    /**
     * Search parents by name/email for linking to student.
     * GET /search-parents?q=...&person=Father|Mother|Guardian
     */
    public function search(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $query = trim($request->get('q', ''));
        $person = $request->get('person', 'Father');

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $parents = ParentModel::where('person', $person)
            ->where(function ($q) use ($query) {
                $q->where('fist_name', 'like', '%' . $query . '%')
                    ->orWhere('last_name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhereRaw("CONCAT(COALESCE(fist_name,''), ' ', COALESCE(last_name,'')) LIKE ?", ['%' . $query . '%'])
                    ->orWhereRaw("CONCAT(COALESCE(last_name,''), ' ', COALESCE(fist_name,'')) LIKE ?", ['%' . $query . '%']);
            })
            ->limit(20)
            ->get(['id', 'fist_name', 'last_name', 'email', 'phone']);

        $results = $parents->map(function ($p) {
            return [
                'id' => $p->id,
                'full_name' => trim(($p->fist_name ?? '') . ' ' . ($p->last_name ?? '')),
                'email' => $p->email ?? '',
                'phone' => $p->phone ?? '',
            ];
        });

        return response()->json(['results' => $results->values()]);
    }

    public function Update_Student_Parent(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $rules = [
            'code_student' => 'required|string|max:64',
            'person' => 'required|string|in:Father,Mother,Guardian',
        ];
        if ($request->person === 'Father') {
            $rules['father_id'] = 'required|integer|exists:parents,id';
        }
        if ($request->person === 'Mother') {
            $rules['mother_id'] = 'required|integer|exists:parents,id';
        }
        if ($request->person === 'Guardian') {
            $rules['guardian_id'] = 'required|integer|exists:parents,id';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
                'message' => 'Validation failed.',
                'message_detail' => implode(' ', $validator->errors()->all()),
            ], 422);
        }

        $forbidden = $this->ensureStudentOwnership($request);
        if ($forbidden) {
            return $forbidden;
        }
        $student = $this->getStudentForUser($request->code_student, $request->input('id_us') ?? FacadesSession::get('user.id'));

        $parentId = $request->father_id ?? $request->mother_id ?? $request->guardian_id;
        $parent = ParentModel::find($parentId);
        if (!$parent) {
            return response()->json(['success' => false, 'message' => 'Parent not found.'], 404);
        }

        try {
            DB::beginTransaction();
            $codeParent = $parent->code_parent;

            if ($request->person === 'Father') {
                StudentDetail::where('code_student', $request->code_student)->update(['code_father' => $codeParent]);
                Academic_year::where('code', $student->code_academic)->update(['codeFather' => $codeParent]);
            }
            if ($request->person === 'Mother') {
                StudentDetail::where('code_student', $request->code_student)->update(['code_mother' => $codeParent]);
                Academic_year::where('code', $student->code_academic)->update(['codeMother' => $codeParent]);
            }
            if ($request->person === 'Guardian') {
                StudentDetail::where('code_student', $request->code_student)->update(['code_guardian' => $codeParent]);
                Academic_year::where('code', $student->code_academic)->update(['codeGuardian' => $codeParent]);
            }

            $academicYear = AcademicYear::where('etat', 1)->first()?->year ?? date('Y');
            AdmissionStep::where('code_stud', $request->code_student)->update([
                'step' => 'step5',
                'academicyear' => $academicYear,
            ]);

            DB::commit();

            $updated = StudentDetail::where('code_student', $request->code_student)->first();
            $nameF = $updated->code_father ? ParentModel::where('code_parent', $updated->code_father)->first() : null;
            $nameM = $updated->code_mother ? ParentModel::where('code_parent', $updated->code_mother)->first() : null;
            $nameG = $updated->code_guardian ? ParentModel::where('code_parent', $updated->code_guardian)->first() : null;

            $display = function ($p) {
                return $p ? trim(($p->fist_name ?? '') . ' ' . ($p->last_name ?? '')) : null;
            };

            $redirectUrl = url('admission') . '?code_student=' . urlencode($request->code_student) . '&step=step4';
            return response()->json([
                'success' => true,
                'message' => 'Parent linked successfully.',
                'nameF' => $display($nameF),
                'nameM' => $display($nameM),
                'nameG' => $display($nameG),
                'parentName' => $display($parent),
                'person' => $request->person,
                'redirect' => $redirectUrl,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Admission Update_Student_Parent error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
            ], 500);
        }
    }


    public function PostInfo_Step6(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $validator = Validator::make($request->all(), [
            'code_student' => 'required|string|max:64',
            'aggree_one' => 'required|accepted',
            'aggree_two' => 'required|accepted',
            'aggree_three' => 'required|accepted',
            'aggree_for' => 'required|accepted',
            'aggree_five' => 'required|accepted',
        ], [
            'required' => 'The :attribute field is required.',
            'accepted' => 'You must accept this commitment.',
        ], [
            'code_student' => 'Student code',
            'aggree_one' => 'Rules and Regulations agreement',
            'aggree_two' => 'Home School Agreement',
            'aggree_three' => 'School Fees Policies agreement',
            'aggree_for' => 'Commitment of Party Responsible agreement',
            'aggree_five' => 'Parents Declaration agreement',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $detail = collect($errors)->map(fn ($msgs, $field) => (str_replace('_', ' ', ucfirst($field)) . ': ' . implode(' ', $msgs)))->implode("\n• ");
            return response()->json([
                'success' => false,
                'errors' => $errors,
                'message' => 'Validation failed. Please accept all commitments.',
                'message_detail' => $detail ? '• ' . $detail : 'Please check all required checkboxes.',
            ], 422);
        }

        $forbidden = $this->ensureStudentOwnership($request);
        if ($forbidden) {
            return $forbidden;
        }
        $student = $this->getStudentForUser($request->code_student, $request->input('id_us') ?? FacadesSession::get('user.id'));

        $father = $student->code_father ? ParentModel::where('code_parent', $student->code_father)->first() : null;
        $mother = $student->code_mother ? ParentModel::where('code_parent', $student->code_mother)->first() : null;
        $guardian = $student->code_guardian ? ParentModel::where('code_parent', $student->code_guardian)->first() : null;

        $fatherName = $father ? trim(($father->fist_name ?? '') . ' ' . ($father->last_name ?? '')) : 'N/A';
        $fatherContact = $father ? ($father->phone ?? $father->main_mobile ?? 'N/A') : 'N/A';
        $motherName = $mother ? trim(($mother->fist_name ?? '') . ' ' . ($mother->last_name ?? '')) : 'N/A';
        $motherContact = $mother ? ($mother->phone ?? $mother->main_mobile ?? 'N/A') : 'N/A';
        $guardianName = $guardian ? trim(($guardian->fist_name ?? '') . ' ' . ($guardian->last_name ?? '')) : 'N/A';
        $guardianContact = $guardian ? ($guardian->phone ?? $guardian->main_mobile ?? 'N/A') : 'N/A';

        try {
            DB::beginTransaction();

            Commitment::updateOrCreate(
                ['code_student' => $request->code_student],
                [
                    'id_userCM' => $request->id_us ?? $student->id_userS,
                    'code_academic' => $student->code_academic,
                    'aggree_one' => 1,
                    'aggree_two' => 1,
                    'aggree_three' => 1,
                    'aggree_for' => 1,
                    'aggree_five' => 1,
                    'father_name' => $fatherName,
                    'father_contact' => $fatherContact,
                    'mother_name' => $motherName,
                    'mother_contact' => $motherContact,
                    'guardian_name' => $guardianName,
                    'guardian_contact' => $guardianContact,
                    'date_end' => date('d-m-Y'),
                    'heur_end' => date('H:i:s'),
                    'etat' => 1,
                ]
            );

            $academicYear = AcademicYear::where('etat', 1)->first()?->year ?? date('Y');
            AdmissionStep::where('code_stud', $request->code_student)->update([
                'step' => 'step6',
                'academicyear' => $academicYear,
            ]);

            \App\Models\User::where('id_us', $student->id_userS)->update(['end_registration' => 'end']);
            Academic_year::where('code', $student->code_academic)->update(['registration' => 'process_end']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Step 6 saved successfully. Application submitted.',
                'redirect' => url('/home-admission'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Admission PostInfo_Step6 error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving. Please try again.',
            ], 500);
        }
    }


    public function getStep1Data(Request $request)
    {
        $acaYear = AcademicYear::where('etat', 1)->first();
        $codeStudent = $request->code_student;
        $step1info = $codeStudent
            ? StudentDetail::query()
                ->leftJoin('files', 'files.code_student', '=', 'StudentDetails.code_student')
                ->leftJoin('academic_year', 'academic_year.code', '=', 'StudentDetails.code_academic')
                ->where('StudentDetails.code_student', $codeStudent)
                ->select('StudentDetails.*', 'files.filepassport', 'files.fileacademi', 'files.fileexams', 'files.filevacc', 'academic_year.class_current')
                ->first()
            : null;

        $classe = Classe::with('subLevel.level')->where('aca_year', $acaYear->year)->get();
        $national = Nationality::all();
        $relation = Relationship::all();
        $fatherparent = ParentModel::where('person', 'Father')->get();
        $motherparent = ParentModel::where('person', 'Mother')->get();
        $parent = ParentModel::where('person', 'Guardian')->get();

        $requiredFilesByClass = $this->getRequiredFilesByClass($classe, $acaYear->year ?? '');
        $countries = Country::orderBy('nom_en_gb')->get();

        $data = [
            'step1info' => $step1info ?? (object) [],
            'relations' => $relation,
            'national' => $national,
            'countries' => $countries,
            'parents' => $parent,
            'motherparents' => $motherparent,
            'fatherparents' => $fatherparent,
            'classes' => $classe,
            'acayear' => $acaYear,
            'requiredFilesByClass' => $requiredFilesByClass,
        ];

        return view('admissions.admission')->with($data);
    }


    public function getStep2Data(Request $request)
    {
        $codeStudent = $request->code_student;
        $medical = $codeStudent ? Medical::where('code_student', $codeStudent)->first() : null;
        $emergency = $codeStudent ? Emergency::where('code_student', $codeStudent)->first() : null;
        $step2info = new \stdClass;
        if ($medical) {
            foreach ($medical->toArray() as $k => $v) {
                $step2info->$k = $v;
            }
        }
        if ($emergency) {
            foreach ($emergency->toArray() as $k => $v) {
                $step2info->$k = $v;
            }
        }

        $acaYear = AcademicYear::where('etat', 1)->first() ?? (object) ['year' => date('Y')];
        $step1info = $codeStudent
            ? StudentDetail::query()
                ->leftJoin('files', 'files.code_student', '=', 'StudentDetails.code_student')
                ->leftJoin('academic_year', 'academic_year.code', '=', 'StudentDetails.code_academic')
                ->where('StudentDetails.code_student', $codeStudent)
                ->select('StudentDetails.*', 'files.filepassport', 'files.fileacademi', 'files.fileexams', 'files.filevacc', 'academic_year.class_current')
                ->first()
            : null;
        $classe = Classe::with('subLevel.level')->where('aca_year', $acaYear->year)->get();
        $national = Nationality::all();
        $relation = Relationship::all();
        $fatherparent = ParentModel::where('person', 'Father')->get();
        $motherparent = ParentModel::where('person', 'Mother')->get();
        $parent = ParentModel::where('person', 'Guardian')->get();

        $requiredFilesByClass = $this->getRequiredFilesByClass($classe, $acaYear->year ?? '');
        $countries = Country::orderBy('nom_en_gb')->get();

        $step3info = $codeStudent ? LearningBehavioural::where('code_student', $codeStudent)->first() : null;

        $data = [
            'step2info' => $step2info,
            'step3info' => $step3info,
            'relations' => $relation,
            'national' => $national,
            'countries' => $countries,
            'parents' => $parent,
            'motherparents' => $motherparent,
            'fatherparents' => $fatherparent,
            'acayear' => $acaYear,
            'step1info' => $step1info ?? (object) [],
            'classes' => $classe,
            'requiredFilesByClass' => $requiredFilesByClass,
        ];

        return view('admissions.admission')->with($data);
    }


    public function getStep3Data(Request $request)
    {
        $codeStudent = $request->code_student;
        $step3info = $codeStudent ? LearningBehavioural::where('code_student', $codeStudent)->first() : null;
        $step4info = $codeStudent ? ChildPickup::where('code_student', $codeStudent)->first() : null;

        $acaYear = AcademicYear::where('etat', 1)->first() ?? (object) ['year' => date('Y')];
        $step1info = $codeStudent
            ? StudentDetail::query()
                ->leftJoin('files', 'files.code_student', '=', 'StudentDetails.code_student')
                ->leftJoin('academic_year', 'academic_year.code', '=', 'StudentDetails.code_academic')
                ->where('StudentDetails.code_student', $codeStudent)
                ->select('StudentDetails.*', 'files.filepassport', 'files.fileacademi', 'files.fileexams', 'files.filevacc', 'academic_year.class_current')
                ->first()
            : null;
        $medical = $codeStudent ? Medical::where('code_student', $codeStudent)->first() : null;
        $emergency = $codeStudent ? Emergency::where('code_student', $codeStudent)->first() : null;
        $step2info = new \stdClass;
        if ($medical) {
            foreach ($medical->toArray() as $k => $v) {
                $step2info->$k = $v;
            }
        }
        if ($emergency) {
            foreach ($emergency->toArray() as $k => $v) {
                $step2info->$k = $v;
            }
        }

        $classe = Classe::with('subLevel.level')->where('aca_year', $acaYear->year)->get();
        $national = Nationality::all();
        $relation = Relationship::all();
        $fatherparent = ParentModel::where('person', 'Father')->get();
        $motherparent = ParentModel::where('person', 'Mother')->get();
        $parent = ParentModel::where('person', 'Guardian')->get();

        $requiredFilesByClass = $this->getRequiredFilesByClass($classe, $acaYear->year ?? '');

        $step4father = null;
        $step4mother = null;
        $step4guardian = null;
        $step5info = null;
        if ($codeStudent) {
            $student = StudentDetail::where('code_student', $codeStudent)->first();
            if ($student) {
                if (!empty($student->code_father)) $step4father = ParentModel::where('code_parent', $student->code_father)->first();
                if (!empty($student->code_mother)) $step4mother = ParentModel::where('code_parent', $student->code_mother)->first();
                if (!empty($student->code_guardian)) $step4guardian = ParentModel::where('code_parent', $student->code_guardian)->first();
            }
            $step5info = Commitment::where('code_student', $codeStudent)->first();
        }

        $countries = Country::orderBy('nom_en_gb')->get();

        $data = [
            'step3info' => $step3info,
            'step4info' => $step4info,
            'step2info' => $step2info,
            'step1info' => $step1info ?? (object) [],
            'step4father' => $step4father,
            'step4mother' => $step4mother,
            'step4guardian' => $step4guardian,
            'step5info' => $step5info,
            'relations' => $relation,
            'national' => $national,
            'countries' => $countries,
            'parents' => $parent,
            'motherparents' => $motherparent,
            'fatherparents' => $fatherparent,
            'acayear' => $acaYear,
            'classes' => $classe,
            'requiredFilesByClass' => $requiredFilesByClass,
        ];

        return view('admissions.admission')->with($data);
    }


    public function getStep4Data(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $codeStudent = $request->code_student;
        $acaYear = AcademicYear::where('etat', 1)->first() ?? (object) ['year' => date('Y')];
        $step1info = $codeStudent
            ? StudentDetail::query()
                ->leftJoin('files', 'files.code_student', '=', 'StudentDetails.code_student')
                ->leftJoin('academic_year', 'academic_year.code', '=', 'StudentDetails.code_academic')
                ->where('StudentDetails.code_student', $codeStudent)
                ->select('StudentDetails.*', 'files.filepassport', 'files.fileacademi', 'files.fileexams', 'files.filevacc', 'academic_year.class_current')
                ->first()
            : null;
        $step2info = new \stdClass;
        if ($codeStudent) {
            $medical = Medical::where('code_student', $codeStudent)->first();
            $emergency = Emergency::where('code_student', $codeStudent)->first();
            if ($medical) {
                foreach ($medical->toArray() as $k => $v) {
                    $step2info->$k = $v;
                }
            }
            if ($emergency) {
                foreach ($emergency->toArray() as $k => $v) {
                    $step2info->$k = $v;
                }
            }
        }
        $step3info = $codeStudent ? LearningBehavioural::where('code_student', $codeStudent)->first() : null;
        $step4info = $codeStudent ? ChildPickup::where('code_student', $codeStudent)->first() : null;

        $step4father = null;
        $step4mother = null;
        $step4guardian = null;
        if ($codeStudent) {
            $student = StudentDetail::where('code_student', $codeStudent)->first();
            if ($student) {
                if (!empty($student->code_father)) {
                    $step4father = ParentModel::where('code_parent', $student->code_father)->first();
                }
                if (!empty($student->code_mother)) {
                    $step4mother = ParentModel::where('code_parent', $student->code_mother)->first();
                }
                if (!empty($student->code_guardian)) {
                    $step4guardian = ParentModel::where('code_parent', $student->code_guardian)->first();
                }
            }
        }

        $step5info = $codeStudent ? Commitment::where('code_student', $codeStudent)->first() : null;

        $classe = Classe::with('subLevel.level')->where('aca_year', $acaYear->year)->get();
        $national = Nationality::all();
        $relation = Relationship::all();
        $fatherparent = ParentModel::where('person', 'Father')->get();
        $motherparent = ParentModel::where('person', 'Mother')->get();
        $parent = ParentModel::where('person', 'Guardian')->get();

        $requiredFilesByClass = $this->getRequiredFilesByClass($classe, $acaYear->year ?? '');
        $countries = Country::orderBy('nom_en_gb')->get();

        $data = [
            'step3info' => $step3info,
            'step4info' => $step4info,
            'step4father' => $step4father,
            'step4mother' => $step4mother,
            'step4guardian' => $step4guardian,
            'step1info' => $step1info ?? (object) [],
            'step2info' => $step2info,
            'step3info' => $step3info,
            'step4info' => $step4info,
            'step5info' => $step5info,
            'relations' => $relation,
            'national' => $national,
            'countries' => $countries,
            'parents' => $parent,
            'motherparents' => $motherparent,
            'fatherparents' => $fatherparent,
            'acayear' => $acaYear,
            'classes' => $classe,
            'requiredFilesByClass' => $requiredFilesByClass,
        ];

        return view('admissions.admission')->with($data);
    }


    public function getStep5Data(Request $request)
    {
        $auth = $this->checkAuth();
        if ($auth) {
            return $auth;
        }

        $codeStudent = $request->code_student;
        $step5info = $codeStudent ? Commitment::where('code_student', $codeStudent)->first() : null;

        $acaYear = AcademicYear::where('etat', 1)->first() ?? (object) ['year' => date('Y')];
        $step1info = $codeStudent
            ? StudentDetail::query()
                ->leftJoin('files', 'files.code_student', '=', 'StudentDetails.code_student')
                ->leftJoin('academic_year', 'academic_year.code', '=', 'StudentDetails.code_academic')
                ->where('StudentDetails.code_student', $codeStudent)
                ->select('StudentDetails.*', 'files.filepassport', 'files.fileacademi', 'files.fileexams', 'files.filevacc', 'academic_year.class_current')
                ->first()
            : null;
        $step2info = new \stdClass;
        if ($codeStudent) {
            $medical = Medical::where('code_student', $codeStudent)->first();
            $emergency = Emergency::where('code_student', $codeStudent)->first();
            if ($medical) {
                foreach ($medical->toArray() as $k => $v) {
                    $step2info->$k = $v;
                }
            }
            if ($emergency) {
                foreach ($emergency->toArray() as $k => $v) {
                    $step2info->$k = $v;
                }
            }
        }
        $step3info = $codeStudent ? LearningBehavioural::where('code_student', $codeStudent)->first() : null;
        $step4info = $codeStudent ? ChildPickup::where('code_student', $codeStudent)->first() : null;
        $step4father = null;
        $step4mother = null;
        $step4guardian = null;
        if ($codeStudent) {
            $student = StudentDetail::where('code_student', $codeStudent)->first();
            if ($student) {
                if (!empty($student->code_father)) {
                    $step4father = ParentModel::where('code_parent', $student->code_father)->first();
                }
                if (!empty($student->code_mother)) {
                    $step4mother = ParentModel::where('code_parent', $student->code_mother)->first();
                }
                if (!empty($student->code_guardian)) {
                    $step4guardian = ParentModel::where('code_parent', $student->code_guardian)->first();
                }
            }
        }

        $classe = Classe::with('subLevel.level')->where('aca_year', $acaYear->year)->get();
        $national = Nationality::all();
        $relation = Relationship::all();
        $fatherparent = ParentModel::where('person', 'Father')->get();
        $motherparent = ParentModel::where('person', 'Mother')->get();
        $parent = ParentModel::where('person', 'Guardian')->get();

        $requiredFilesByClass = $this->getRequiredFilesByClass($classe, $acaYear->year ?? '');
        $countries = Country::orderBy('nom_en_gb')->get();

        $data = [
            'step5info' => $step5info,
            'step6info' => $step5info,
            'step1info' => $step1info ?? (object) [],
            'step2info' => $step2info,
            'step3info' => $step3info,
            'step4info' => $step4info,
            'step4father' => $step4father,
            'step4mother' => $step4mother,
            'step4guardian' => $step4guardian,
            'relations' => $relation,
            'national' => $national,
            'countries' => $countries,
            'parents' => $parent,
            'motherparents' => $motherparent,
            'fatherparents' => $fatherparent,
            'acayear' => $acaYear,
            'classes' => $classe,
            'requiredFilesByClass' => $requiredFilesByClass,
        ];

        return view('admissions.admission')->with($data);
    }


    public function getStep6Data(Request $request)
    {
        return $this->getStep5Data($request);
    }

    public function getstudeInprocess($id)
    {
        // $codeStudent = $request->code_student;
        $studinproces = DB::table('academic_year')
            ->where('id_user', $id)->where('registration', 'in_process')->orWhere('registration', 'process_end')->get();

        $data = [
            'studinprocess' => $studinproces,
        ];

        return view('admissions.in-process')->with($data);
    }

    public function getstudeItemsOrdering($id)
    {
        // $codeStudent = $request->code_student;
        $studitemsorde = DB::table('academic_year')
            ->where('id_user', $id)->where('registration', 'Items_ordering')->get();

        $data = [
            'studitemsordes' => $studitemsorde,
        ];

        return view('admissions.items-ordering')->with($data);
    }

    public function getstudeRegistration($code)
    {
        $studinfo = DB::table('academic_year')
            ->join('StudentDetails', 'StudentDetails.code_academic', '=', 'academic_year.code')
            ->where('academic_year.code', $code)
            ->first();

        $acaYear = DB::table('academicyear')
            ->where('etat', 1)
            ->limit(1)
            ->first();

        $classe = DB::table("classes")->where('aca_year', $acaYear->year)->get();

        // dd($studinfo);
        // return $studinfo;

        $fatherinfo = DB::table('academic_year')
            ->join('parents', 'parents.code_parent', '=', 'academic_year.codeFather')
            ->where('code', $code)->first();

        $motherinfo = DB::table('academic_year')
            ->join('parents', 'parents.code_parent', '=', 'academic_year.codeMother')
            ->where('code', $code)->first();

        $guardianinfo = DB::table('academic_year')
            ->join('parents', 'parents.code_parent', '=', 'academic_year.codeGuardian')
            ->where('code', $code)->first();

        $medicalinfo = DB::table('academic_year')
            ->join('medical', 'medical.code_academic', '=', 'academic_year.code')
            ->where('code', $code)->first();

        $emmergeinfo = DB::table('academic_year')
            ->join('emergency', 'emergency.code_academic', '=', 'academic_year.code')
            ->join('child_pickup', 'child_pickup.code_academic', '=', 'emergency.code_academic')
            ->where('code', $code)->first();

        $data = [
            'studinfo' => $studinfo,
            'fatherinfos' => $fatherinfo,
            'motherinfos' => $motherinfo,
            'guardianinfos' => $guardianinfo,
            'medicalinfo' => $medicalinfo,
            'emmergeinfo' => $emmergeinfo,
            'classes' => $classe,
        ];

        return view('admissions.registration-validate')->with($data);
    }

    public function getstudeAdmission(Request $request)
    {

        // return $request->all();
        $acaYear = DB::table('academicyear')
            ->where('etat', 1)
            ->limit(1)
            ->first();

        $provin = DB::table('proforma_invoice')
            ->where('class', $request->classroom)
            ->where('academicyear', $acaYear->year)
            ->get();

        // $studaca = DB::table('academic_year')
        //     ->where('code', $request->code_academic)->first();

        DB::table('academic_year')
            ->where('code', $request->code_academic)
            ->update([
                'classroom' => $request->classroom,
                'registration' => 'items_ordering',
            ]);

        $size = DB::table("articles_sizes")->get();

        $data = [
            'provins' => $provin,
            'code_academic' => $request->code_academic,
            'sizes' => $size,
            'classroom' => $request->classroom,

        ];

        return view('admissions.items-ordering-validate')->with($data);
    }

    public function getstudeAdmission2($clas, $code)
    {
        // return $request->all();
        $acaYear = DB::table('academicyear')
            ->where('etat', 1)
            ->limit(1)
            ->first();

        $provin = DB::table('proforma_invoice')
            ->where('class', $clas)
            ->where('academicyear', $acaYear->year)
            ->get();

        $size = DB::table("articles_sizes")->get();

        $data = [
            'provins' => $provin,
            'sizes' => $size,
            'code_academic' => $code,
            'classroom' => $clas,
        ];

        return view('admissions.items-ordering-validate')->with($data);
    }

    public function increment_n($max_value, $last_number, $prefix = '', $padding = 0)
    {
        $last_number = (int)$last_number;
        $max_value = (int)$max_value;

        $next_number = ($last_number >= $max_value) ? 1 : $last_number + 1;

        // Ajouter un padding si nécessaire (ex: 00001)
        if ($padding > 0) {
            $next_number = str_pad($next_number, $padding, '0', STR_PAD_LEFT);
        }

        return $prefix . $next_number;
    }

    protected function incrementNumber($taille_maxi, $numpreceent)
    {
        $numInt = intval($numpreceent);
        $numInt++;

        $tailleMaxStr = strval($taille_maxi);
        $nbChiffres = strlen($tailleMaxStr);

        if (strlen(strval($numInt)) > $nbChiffres) {
            $nbChiffres = strlen(strval($numInt));
        }

        return str_pad($numInt, $nbChiffres, '0', STR_PAD_LEFT);
    }

    public function getstudeAdmission3(Request $request)
    {
        $acaYear = DB::table('academicyear')
            ->where('etat', 1)
            ->limit(1)
            ->first();

        $academicyear = DB::table('academicyear')->get();

        // return $request->all();
        $unit_price = $request->unit_price;
        $id_article = $request->id_article;
        $description = $request->description;
        $qtty = $request->qtty;
        $concession = $request->concession;
        $amount_concession = $request->amount_concession;
        $amount = $request->amount;
        $size = $request->size;

        $student = DB::table('StudentDetails')
            ->where('code_academic', $request->code_academic)
            ->first();

        $academicYearRecord = DB::table('academic_year')
            ->where('academicyear', $acaYear->year)
            ->where('code', $request->code_academic)
            ->first();

        if ($academicYearRecord && !empty($academicYearRecord->number)) {
            $num = str_pad((int)$academicYearRecord->number, 5, '0', STR_PAD_LEFT);
            $registrationNumber = $academicYearRecord->registration_number ?: $academicYearRecord->admission_number;
            if (empty($registrationNumber)) {
                $registrationNumber = $this->formatAdmissionRegistrationNumber(
                    $student->nom ?? '',
                    $student->prenom ?? '',
                    $num,
                    $request->classroom,
                    $acaYear->year
                );
            }
        } else {
            $admissionNumberData = $this->buildAdmissionNumberData(
                $student->nom ?? '',
                $student->prenom ?? '',
                $request->classroom,
                $acaYear->year
            );
            $num = $admissionNumberData['number_admission'];
            $registrationNumber = $admissionNumberData['registration_number'];
        }

        // return $num;
        $id_fact = "PI" . "" . date('dmY') . "/00" . $num;

        DB::table('academic_year')
            ->where('academicyear', $acaYear->year)
            ->where('code', $request->code_academic)
            ->update([
                'registration_number' => $registrationNumber,
                'admission_number' => $registrationNumber,
                'number' => (int)$num,
                'number_admission' => $num,
                'admission_date' => date('d-m-Y'),
                'registration' => 'Waiting_Installment', // status Items_ordering (Commande d'articles)
                'invoice' => 'invoice_' . $request->codes . '_' . str_replace('-', '_', $acaYear->year),
                'etat' => 1,
                'actif' => 0,
            ]);

        foreach ($qtty as $key => $value) {

            $fist = DB::table("invoices")
                ->where('code_academic', $request->code_academic)
                ->where('productID', $id_article[$key])
                ->where('classroom', $request->classroom)
                ->first();

            if (empty($fist)) {
                // return $amount[$key];
                DB::table('invoices')->insert([
                    'code_academic' => $request->code_academic,
                    'id_stud' => $student->id_stud,
                    'id_fact' => $id_fact,
                    'productID' => $id_article[$key],
                    'classroom' => $request->classroom,
                    'product' => $description[$key],
                    'price' => $unit_price[$key],
                    'qty' => $value,
                    'size' => $size[$key],
                    'concession' => $concession[$key],
                    'amount_concession' => $amount_concession[$key],
                    'amount' => $amount[$key],

                ]);

                DB::table('proforma_invoice')
                    ->where('academicyear', $acaYear->year)
                    ->where('id_article', $id_article[$key])
                    ->update([
                        'qtty' => $qtty[$key],
                        'amount_proforma' => $amount[$key],
                    ]);
            } else {

                DB::table('invoices')
                    ->update([
                        'qty' => $value,
                        'size' => $size[$key],
                        'amount' => $amount[$key],

                    ]);

                DB::table('proforma_invoice')
                    ->where('academicyear', $acaYear->year)
                    ->where('id_article', $id_article[$key])
                    ->update([
                        'qtty' => $qtty[$key],
                        'amount_proforma' => $amount[$key],
                    ]);
            }
        }

        // DB::table('academic_year')
        //     ->where('code', $request->code_academic)
        //     ->update([
        //         'registration' => 'Waiting_Installment',
        //     ]);

        $waitInstalment = DB::table('academic_year')
            ->join('parents', function ($join) {
                $join->on('parents.code_parent', '=', 'academic_year.codeFather')
                    ->orOn('parents.code_parent', '=', 'academic_year.codeMother')
                    ->orOn('parents.code_parent', '=', 'academic_year.codeGuardian');
            })
            // ->groupBy('academic_year.id') // Grouper par ID de l'année académique
            ->where('academic_year.id_user', $student->id_userS)
            ->where('academic_year.registration', 'Waiting_Installment')
            ->where('parents.responsible_of_school_fees', 'Yes')
            // ->distinct() // Évite les doublons
            ->get();
        // ->join('parents as father', 'father.code_parent', '=', 'academic_year.codeFather')
        // ->join('parents as mother', 'mother.code_parent', '=', 'academic_year.codeMother')
        // ->join('parents as guardian', 'guardian.code_parent', '=', 'academic_year.codeGuardian')
        // ->leftJoin('parents as father', 'father.code_parent', '=', 'StudentDetails.code_father')
        // ->leftJoin('parents as mother', 'mother.code_parent', '=', 'StudentDetails.code_mother')
        // ->leftJoin('parents as guardian', 'guardian.code_parent', '=', 'StudentDetails.code_guardian')
        // ->join('parents', 'parents.code_parent', '=', 'StudentDetails.code_father')
        // ->join('parents', 'parents.code_parent', '=', 'StudentDetails.code_mother')
        // ->join('parents', 'parents.code_parent', '=', 'StudentDetails.code_guardian')
        // ->where('code_student', $codeStud)->first();
        // ->join('parents as father', 'father.code_parent', '=', 'StudentDetails.code_father')
        // ->join('parents as mother', 'mother.code_parent', '=', 'StudentDetails.code_mother')
        // ->join('parents as guardian', 'guardian.code_parent', '=', 'StudentDetails.code_guardian')
        // ->where('StudentDetails.code_student', $codeStud)
        // ->first();


        // $codeStudent = $request->code_student;
        $data = [
            'academicyears' => $academicyear,
            'acaYear' => $acaYear,
            'waitInstalments' => $waitInstalment,
        ];

        return view('instalment.waiting-for-instalment')->with($data);
    }


    public function waitingForInstalment($id)
    {
        $academicyear = DB::table('academicyear')->get();
        $acaYear = DB::table('academicyear')
            ->where('etat', 1)
            ->limit(1)
            ->first();

        // return $waitInstalment = DB::table('academic_year') 
        $waitInstalment = DB::table('academic_year')
            // ->select('parents.*','academic_year.admission_date')
            ->select('academic_year.first_name', 'academic_year.admission_date', 'academic_year.classroom', 'academic_year.nature', 'academic_year.statut', 'parents.id', 'parents.code_parent', 'parents.last_name', 'parents.fist_name', 'parents.person', 'parents.email', 'parents.main_mobile')
            ->join('parents', function ($join) {
                $join->on('parents.code_parent', '=', 'academic_year.codeFather')
                    ->orOn('parents.code_parent', '=', 'academic_year.codeMother')
                    ->orOn('parents.code_parent', '=', 'academic_year.codeGuardian');
            })
            ->where('academic_year.id_user', $id)
            // ->where('academicyear', $acaYear->year)
            ->where('academic_year.registration', 'Waiting_Installment')
            ->where('parents.responsible_of_school_fees', 'Yes')
            ->get();


        $data = [
            'academicyears' => $academicyear,
            'acaYear' => $acaYear,
            'waitInstalments' => $waitInstalment,
        ];

        return view('instalment.waiting-for-instalment')->with($data);
    }


    public function waitingInstalment($code)
    {
        // return $code;

        $acaYear = DB::table('academicyear')
            ->where('etat', 1)
            ->limit(1)
            ->first();

        $Instalment = DB::table('instalment_submit')
            // ->select('academic_year.*','parents.code_parent','parents.last_name')
            ->where('code_parent', $code)
            ->where('academicyear', $acaYear->year)
            ->get();

        $data = [
            'acaYear' => $acaYear,
            'Instalments' => $Instalment,
        ];

        return view('instalment.view-waiting-instalment')->with($data);
    }


    public function Instalmentstep($id)
    {
        // return $id;
        // $proforma_invoice = [];
        $Instalment = [];
        $amount = 0;
        $totalinvoice = 0;
        $totalconce = 0;
        $totalarti = 0;
        $academicyear = DB::table('academicyear')->get();

        $acaYear = DB::table('academicyear')
            ->where('etat', 1)
            ->limit(1)
            ->first();


        $parent = DB::table('parents')
            ->where('id', $id)
            // ->where('id', $id)
            ->first();


        // $user = DB::table('users')
        // ->where('code_parent', $parent->code_parent)
        // // ->where('id', $id)
        // ->first();

        $stud = DB::table('academic_year')
            ->where('academicyear', $acaYear->year)
            ->where(function ($query) use ($parent) {
                $query->where('codeFather', $parent->code_parent)
                    ->orWhere('codeMother', $parent->code_parent)
                    ->orWhere('codeGuardian', $parent->code_parent);
            })
            ->get();

        // dd($stud->count()); // Affiche le nombre de résultats

        $instal = DB::table('instalment_submit')->select('balance_paid')
            ->where('code_parent', $parent->code_parent)
            ->where('academicyear', $acaYear->year)
            ->get();

        $firstinstal = DB::table('instalment_submit')->select('balance_paid')
            ->where('instalment', '0')
            ->where('code_parent', $parent->code_parent)
            ->where('academicyear', $acaYear->year)
            ->first();

        // $article_item = DB::table('invoices')
        // // return $article_item = DB::table('articles')
        // ->join('articles', 'articles.description', '=', 'invoices.product')
        // // ->join('invoices', 'invoices.productID', '=', 'articles.id')
        // ->where('articles.academicyear', $acaYear->year)
        // ->where('invoices.code_academic', $stud->code)
        // ->get();


        foreach ($instal as $value) {
            // return $value->balance_paid;
            $amount += $value->balance_paid;
        }

        foreach ($stud as $value) {
            // dd($value->code);
            $proforma_invoice = DB::table('invoices')
                ->where('code_academic', $value->code)
                ->get();


            $article_item = DB::table('invoices')
                // return $article_item = DB::table('articles')
                ->join('articles', 'articles.description', '=', 'invoices.product')
                // ->join('invoices', 'invoices.productID', '=', 'articles.id')
                ->where('articles.academicyear', $acaYear->year)
                ->where('invoices.code_academic', $value->code)
                ->get();

            $Instalmen = DB::table('recu')
                ->select('recu.*', 'academic_year.last_name', 'academic_year.first_name', 'classes.name_classe')
                ->join('academic_year', 'academic_year.code', '=', 'recu.code_academic')
                ->join('classes', 'classes.id', '=', 'academic_year.id_class')
                ->where('recu.code_academic', $value->code)
                ->where('recu.academicyear', $acaYear->year)
                // ->where('recu.academicyear', $value->academicyear)
                // ->where('academic_year.code', $value->code)
                // ->where('academic_year.academicyear', $acaYear->year)
                ->first();

            $Instalment[] = $Instalmen;

            // $proforma_invoice[] = $proforma;

            foreach ($proforma_invoice as $value) {
                // $value->amount;
                $totalinvoice += $value->amount;
                $totalconce += $value->concession;
            }
        }


        foreach ($article_item as $value) {
            // $value->amount;
            $totalarti += $value->price;
        }

        $totalarti;
        $halfarticle = $totalarti / 2;
        $balance = ($totalarti + $totalinvoice) - ($totalinvoice + $halfarticle);

        // return $amount;

        // foreach ($proforma_invoice as $invoice => $value) {
        //     // return $value->balance_paid;
        //     $totalinvoice += $value->amount;
        // }

        // return $totalinvoice;

        $instal = DB::table('instalment')->get();

        $data = [
            'academicyears' => $academicyear,
            'acaYear' => $acaYear,
            'Instalments' => $Instalment,
            'amount' => $amount,
            'firstinstal' => $firstinstal,
            'totalinvoice' => $totalinvoice,
            'totalconce' => $totalconce,
            'totalarti' => $totalarti,
            'halfarticle' => $halfarticle,
            'instals' => $instal,
            'balance' => $balance,
        ];

        return view('instalment.instalment')->with($data);
    }

    public function instalment1(Request $request)
    {
        // // return $request->all();
        // $pickphoto1 = null;
        // if ($request->hasFile('file')) {
        //     $pickphoto1 = $request->file('file')->store('student_photos', 'public');
        // }

        // // Vérifier si le fichier existe
        // if (FacadesStorage::disk('public')->exists($pickphoto1)) {
        //     echo "Fichier enregistré avec succès";
        // } else {
        //     // Problème d'enregistrement
        // }
        // // return view('instalment.view-waiting-instalment');


        $pickphoto1 = null;
        if ($request->hasFile('file')) {
            try {
                // 1. Vérifier le fichier
                $file = $request->file('file');

                if (!$file->isValid()) {
                    throw new \Exception('Fichier invalide. Erreur: ' . $file->getErrorMessage());
                }

                Log::info('Tentative upload', [
                    'nom' => $file->getClientOriginalName(),
                    'taille' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ]);

                // 2. Enregistrement dans le dossier public (pas storage)
                $directory = 'uploads/student_photos';
                $fullDirectoryPath = public_path($directory);

                if (!file_exists($fullDirectoryPath)) {
                    if (!mkdir($fullDirectoryPath, 0755, true)) {
                        throw new \Exception('Impossible de créer le dossier: ' . $fullDirectoryPath);
                    }
                    Log::info('Dossier créé: ' . $fullDirectoryPath);
                }

                // 3. Vérifier les permissions
                if (!is_writable($fullDirectoryPath)) {
                    throw new \Exception('Dossier non accessible en écriture: ' . $fullDirectoryPath);
                }

                // 4. Enregistrer le fichier dans public
                $extension = $file->getClientOriginalExtension();
                $filename = 'student_' . time() . '_' . uniqid() . '.' . $extension;
                $file->move($fullDirectoryPath, $filename);
                $pickphoto1 = $directory . '/' . $filename;

                Log::info('Résultat enregistrement public', [
                    'chemin' => $pickphoto1,
                    'filename' => $filename,
                ]);

                // 5. Vérifier physiquement
                $fullPath = public_path($pickphoto1);

                if (!file_exists($fullPath)) {
                    throw new \Exception('Fichier non créé physiquement à: ' . $fullPath);
                }

                Log::info('SUCCÈS - Fichier uploadé (public)', [
                    'chemin_bd' => $pickphoto1,
                    'chemin_physique' => $fullPath,
                    'taille_fichier' => filesize($fullPath),
                ]);
            } catch (\Exception $e) {
                Log::error('ÉCHEC upload', [
                    'erreur' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                // Optionnel: retourner à la vue avec erreur
                return back()->with('error', 'Erreur upload: ' . $e->getMessage());
            }

        }

        // Pour voir le résultat final
    }
}
