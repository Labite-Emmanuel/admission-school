@if (Route::is([
'horizontal-timeline',
'ui-accordion',
'ui-alerts',
'ui-avatar',
'ui-badges',
'ui-borders',
'ui-breadcrumb',
'ui-buttons',
'ui-buttons-group',
'ui-cards',
'ui-carousel',
'ui-colors',
'ui-grid',
'ui-images',
'ui-lightbox',
'ui-media',
'ui-modals',
'ui-nav-tabs',
'ui-notification',
'ui-offcanvas',
'ui-pagination',
'ui-placeholders',
'ui-popovers',
'ui-progress',
'ui-toasts',
'ui-typography',
'ui-video',
'ui-dropdowns',
]))
<!-- Page Header -->
<div class="page-header">
    <div class="page-title">
        <h3>{{ $title }}</h3>
    </div>
</div>
<!-- /Page Header -->

@endif

@if (Route::is([
'chart-apex',
'chart-c3',
'chart-flot',
'chart-js',
'chart-morris',
'chart-peity',
'icon-feather',
'icon-flag',
'icon-fontawesome',
'icon-ionic',
'icon-material',
'icon-pe7',
'icon-simpleline',
'icon-themify',
'icon-typicon',
'icon-weather',
'ui-counter',
'ui-clipboard',
'ui-drag-drop',
'ui-rangeslider',
'ui-rating',
'ui-ribbon',
'ui-scrollbar',
'ui-spinner',
'ui-stickynote',
'ui-sweetalerts',
'ui-text-editor',
'ui-timeline',
'ui-tooltips',
'form-basic-inputs',
'form-checkbox-radios',
'form-fileupload',
'form-floating-labels',
'form-grid-gutters',
'form-horizontal',
'form-input-groups',
'form-mask',
'form-select',
'form-select2',
'form-validation',
'form-vertical',
'form-wizard',
'data-tables',
]))
<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">{{ $title }}</h3>
        </div>
    </div>
</div>
<!-- /Page Header -->
@endif

@if(Route::is([
'classes',
'schedule-classes',
'class-room',
'class-routine',
'class-section',
'class-subject',
'class-syllabus',
'class-time-table',
'class-home-work',
'exam',
'exam-schedule',
'grade',
'exam-attendance',
'exam-results',
'academic-reasons',
'fees-group',
'fees-type',
'fees-master',
'fees-assign',
'collect-fees',
'library-members',
'library-books',
'library-issue-book',
'library-return',
'sports',
'players',
'hostel-list',
'hostel-rooms',
'hostel-room-type',
'transport-routes',
'transport-pickup-points',
'transport-vehicle-drivers',
'transport-vehicle',
'transport-assign-vehicle',
'students',
'teachers',
'teacher-grid',
'departments',
'designation',
'student-attendance',
'teacher-attendance',
'staff-attendance',
'holidays',
'payroll',
'expenses',
'expenses-category',
'accounts-income',
'accounts-invoices',
'accounts-transactions',
'attendance-report',
'class-report',
'student-report',
'grade-report',
'leave-report',
'fees-report',
'users',
'roles-permission',
'delete-account',
'membership-plans',
'membership-transactions',
'pages',
'blog-categories',
'blog-comments',
'blog-tags',
'countries',
'states',
'cities',
'testimonials',
'faq',
'academic-years',
'admission-period',
'registration-period',
'first-levels',
'school-years',
'term-name',
'event-name',
'school-event',
'term-management',
'main-settings',
'countrie',
'nationalities',
'user-groups',
'relationship',
'job-description',
'external-schools',
'class-sections',
'class-admission',
'class-teacher-subject',
'teacher-subject',
'student-section',
'student-subject',
'school-authorities',
'cars-brand',
'cars',
'trips',
'assign-trip',
'reason-for',
'coachs',
'activity',
'provider',
'monthly-price',
'type-dish',
'dishe',
'sof-subject',
'sample-invoice',
'bank',
'item-groups',
'type-packages',
'sizes',
'size-charts-name',
'size-charts-determination',
'items',
'providers',
'instalment',
'date-first-instalment',
'election-parameters',
'card-validity',
'class-call',
'class-timetable',
'subjects',
'reason-leave',
'class-time-slot',
'sub-levels',
'class',
'sections',
'files',
'require-files',
'placement-test',
'evaluation-name',
'weightings',
'exams-name',
'exams',
'annual-average',
'promotion-name',
'promotions',
'name-behaviours',
'behaviours',
'name-effort-level',
'effort-level',
'name-grading-scale',
'grading-scale',
'nursery-theme',
'nursery-sub-theme',
'nursery-report',
'param-name',
'in-process',

]))
<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between mb-3">
    <div class="my-auto mb-2">
        <h3 class="page-title mb-1">{{ $title }}</h3>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{url('index')}}">{{ $item1 }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">{{ $item2 }} </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $item3 }} </li>
            </ol>
        </nav>
    </div>
    <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
        <div class="pe-1 mb-2">
            <a href="#" class="btn btn-outline-light bg-white btn-icon me-1" data-bs-toggle="tooltip"
                data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh">
                <i class="ti ti-refresh"></i>
            </a>
        </div>
        <div class="pe-1 mb-2">
            <button type="button" class="btn btn-outline-light bg-white btn-icon me-1"
                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Print"
                data-bs-original-title="Print">
                <i class="ti ti-printer"></i>
            </button>
        </div>
        <div class="dropdown me-2 mb-2">
            <a href="javascript:void(0);"
                class="dropdown-toggle btn btn-light fw-medium d-inline-flex align-items-center"
                data-bs-toggle="dropdown">
                <i class="ti ti-file-export me-2"></i>Export
            </a>
            <ul class="dropdown-menu  dropdown-menu-end p-3">
                <li>
                    <a href="javascript:void(0);" class="dropdown-item rounded-1"><i
                            class="ti ti-file-type-pdf me-1"></i>Export as PDF</a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="dropdown-item rounded-1"><i
                            class="ti ti-file-type-xls me-1"></i>Export as Excel </a>
                </li>
            </ul>
        </div>
        <div class="mb-2">
            @if(Route::is(['classes']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_class"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Class</a>
            @endif
            @if(Route::is(['schedule-classes']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_Schedule"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Schedule</a>
            @endif
            @if(Route::is(['class-room']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#add_class_room"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Class Room</a>
            @endif
            @if(Route::is(['class-routine']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#add_class_routine"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Class Routine</a>
            @endif
            @if(Route::is(['class-section']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#add_class_section"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Section</a>
            @endif
            @if(Route::is(['class-subject']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_subject"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Subject</a>
            @endif
            @if(Route::is(['class-syllabus']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_syllabus"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add Subject Group</a>
            @endif
            @if(Route::is(['class-time-table']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_time_table"><i class="ti ti-square-rounded-plus me-2"></i>Add Time
                Table</a>
            @endif
            @if(Route::is(['class-home-work']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#add_home_work"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Home Work</a>
            @endif
            @if(Route::is(['exam']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_exam"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Exam</a>
            @endif
            @if(Route::is(['exam-schedule']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#add_exam_schedule"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Exam Schedule</a>
            @endif
            @if(Route::is(['grade']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_grade"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Grade</a>
            @endif
            @if(Route::is(['academic-reasons']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_reason"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add Reasons</a>
            @endif
            @if(Route::is(['fees-group']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#add_fees_group"><i
                    class="ti ti-square-rounded-plus me-2"></i>Add
                Fees Group</a>
            @endif
            @if(Route::is(['fees-type']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_fees_Type"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Fees Type</a>
            @endif
            @if(Route::is(['fees-master']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_fees_master"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Fees Master</a>
            @endif
            @if(Route::is(['fees-assign']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_fees_assign"><i class="ti ti-square-rounded-plus me-2"></i>Assign
                New</a>
            @endif
            @if(Route::is(['library-members']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_library_members"><i
                    class="ti ti-square-rounded-plus me-2"></i>Add member</a>
            @endif
            @if(Route::is(['library-books']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_library_book"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Book</a>
            @endif
            @if(Route::is(['sports']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add_sports"><i
                    class="ti ti-square-rounded-plus me-2"></i>Add Sport</a>
            @endif
            @if(Route::is(['players']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_players"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Player</a>
            @endif
            @if(Route::is(['hostel-list']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add_hostel"><i
                    class="ti ti-square-rounded-plus me-2"></i>Add
                Hostel</a>
            @endif
            @if(Route::is(['hostel-rooms']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_hostel_rooms"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Hostel Room</a>
            @endif
            @if(Route::is(['hostel-room-type']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_hostel_room_type"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Room Type</a>
            @endif
            @if(Route::is(['transport-routes']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_routes"><i class="ti ti-square-rounded-plus me-2"></i>Add Route</a>
            @endif
            @if(Route::is(['transport-pickup-points']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add_pickup"><i
                    class="ti ti-square-rounded-plus me-2"></i>Add Pickup Point</a>
            @endif
            @if(Route::is(['transport-vehicle-drivers']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_driver"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Vehicle</a>
            @endif
            @if(Route::is(['transport-vehicle']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_vehicle"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Vehicle</a>
            @endif
            @if(Route::is(['transport-assign-vehicle']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_assign_vehicle"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Assign New Vehicle</a>

            @endif
            @if(Route::is(['students']))
            <a href="{{url('add-student')}}" class="btn btn-primary d-flex align-items-center"><i class="ti ti-square-rounded-plus me-2"></i>Add Student</a>
            @endif
            @if(Route::is(['teachers']))
            <a href="{{url('add-teacher')}}" class="btn btn-primary d-flex align-items-center"><i class="ti ti-square-rounded-plus me-2"></i>Add Teacher</a>
            @endif
            @if(Route::is(['teacher-grid']))
            <a href="{{url('add-teacher')}}" class="btn btn-primary d-flex align-items-center"><i class="ti ti-square-rounded-plus me-2"></i>Add Teacher</a>
            @endif
            @if(Route::is(['departments']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_department"><i
                    class="ti ti-square-rounded-plus me-2"></i>Add Department</a>
            @endif
            @if(Route::is(['designation']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_designation"><i
                    class="ti ti-square-rounded-plus me-2"></i>Add Designation</a>
            @endif
            @if(Route::is(['holidays']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_holiday"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Holiday</a>
            @endif
            @if(Route::is(['expenses']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_expenses"><i class="ti ti-square-rounded-plus me-2"></i>Add Expense</a>
            @endif
            @if(Route::is(['expenses-category']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_expenses_category"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Category</a>
            @endif
            @if(Route::is(['accounts-income']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_income"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Income</a>
            @endif
            @if(Route::is(['accounts-invoices']))
            <a href="{{url('add-invoice')}}" class="btn btn-primary d-flex align-items-center"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Invoices</a>
            @endif

            @if(Route::is(['roles-permission']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_role"><i class="ti ti-square-rounded-plus me-2"></i>Add Role</a>
            @endif
            @if(Route::is(['membership-plans']))
            <a href="#" data-bs-toggle="modal" data-bs-target="#add_membership"
                class="btn btn-primary d-flex align-items-center"><i
                    class="ti ti-square-rounded-plus me-2"></i>Add Membership</a>
            @endif
            @if(Route::is(['pages']))
            <a href="#" class="btn btn-primary d-flex align-items-center"><i
                    class="ti ti-square-rounded-plus me-2"></i>Add Page</a>
            @endif
            @if(Route::is(['blog-categories']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_category"><i class="ti ti-square-rounded-plus me-2"></i>Add
                Category</a>
            @endif
            @if(Route::is(['blog-comments']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add_blog"><i
                    class="ti ti-square-rounded-plus me-2"></i>Add Blog</a>
            @endif
            @if(Route::is(['blog-tags']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_tags"><i class="ti ti-square-rounded-plus me-2"></i>Add Tag</a>
            @endif
            @if(Route::is(['countries']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_country"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Country</a>
            @endif
            @if(Route::is(['states']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_state"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add State</a>
            @endif
            @if(Route::is(['cities']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_cities"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Cities</a>
            @endif
            @if(Route::is(['testimonials']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#add_testimonials"><i
                    class="ti ti-square-rounded-plus-filled me-2"></i>Add
                Testimonials</a>
            @endif
            @if(Route::is(['faq']))
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#add_faq"><i class="ti ti-square-rounded-plus me-2"></i>Add FAQ</a>
            @endif
            @if(Route::is(['academic-years']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_academic_year"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Academic year</a>
            @endif
            @if(Route::is(['admission-period']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_admission_period"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Admission period</a>
            @endif
            @if(Route::is(['first-levels']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_first_level"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add First levels</a>
            @endif
            @if(Route::is(['registration-period']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_registration"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Registration period</a>
            @endif
            @if(Route::is(['school-years']))
            <a href="#" class="btn btn-primary add-btn" data-bs-toggle="modal" data-bs-target="#add_school_year"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add School years</a>
            @endif
            @if(Route::is(['term-management']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_term_manag"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Term management</a>
            @endif
            @if(Route::is(['event-name']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_event_name"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Event name</a>
            @endif
            @if(Route::is(['term-name']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_term_name"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Term name</a>
            @endif
            @if(Route::is(['main-settings']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_main_settings"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Main setting</a>
            @endif
            @if(Route::is(['countrie']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_countrie"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Countries</a>
            @endif
            @if(Route::is(['nationalities']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_nationalitie"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Nationalities</a>
            @endif
            @if(Route::is(['user-groups']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_user_group"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Users group</a>
            @endif
            @if(Route::is(['relationship']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_relationship"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Relationship</a>
            @endif
            @if(Route::is(['job-description']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_job_descrip"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Job description</a>
            @endif
            @if(Route::is(['external-schools']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_external_scho"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add External school</a>
            @endif
            @if(Route::is(['class-sections']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_class_section"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Class Section</a>
            @endif
            @if(Route::is(['class-admission']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_class_admission"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Class Admission</a>
            @endif
            @if(Route::is(['class-teacher-subject']))
            <!-- <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_first_class"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add First Term</a> -->
            @endif
            @if(Route::is(['second-term-subject']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_second_class"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Second Term</a>
            @endif
            @if(Route::is(['third-term-subject']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_third_class"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Third Term</a>
            @endif
            @if(Route::is(['cars-brand']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_cars_brand"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Cars Brands</a>
            @endif
            @if(Route::is(['cars']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_cars"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Cars</a>
            @endif
            @if(Route::is(['trips']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_trips"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Trips</a>
            @endif
            @if(Route::is(['assign-trip']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_assign_trip"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Assign Trip</a>
            @endif
            @if(Route::is(['reason-for']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_reason_for"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Reason for</a>
            @endif
            @if(Route::is(['coachs']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_coach"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Coachs</a>
            @endif
            @if(Route::is(['activity']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_activity"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Activities</a>
            @endif
            @if(Route::is(['provider']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_provider"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Provider</a>
            @endif
            @if(Route::is(['monthly-price']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_monthly_price"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Monthly Price</a>
            @endif
            @if(Route::is(['type-dish']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_type_dish"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Type Dish</a>
            @endif
            @if(Route::is(['dishe']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_dishe"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Dishe</a>
            @endif
            @if(Route::is(['sof-subject']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_sof_subject"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Sof Subject</a>
            @endif
            @if(Route::is(['sample-invoice']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_sample_invoice"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Invoice Sample</a>
            @endif
            @if(Route::is(['bank']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_bank"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Bank</a>
            @endif
            @if(Route::is(['item-groups']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_item_group"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Groups Items</a>
            @endif
            @if(Route::is(['type-packages']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_type_package"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Packages Types</a>
            @endif
            @if(Route::is(['sizes']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_sizes"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Sizes</a>
            @endif
            @if(Route::is(['size-charts-name']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_size_charts_name"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Sizes Charts Name</a>
            @endif
            @if(Route::is(['size-charts-determination']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_size_charts_de"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Sizes Determination</a>
            @endif
            @if(Route::is(['items']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_items"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Items</a>
            @endif
            @if(Route::is(['providers']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_prov"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Provider</a>
            @endif
            @if(Route::is(['instalment']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_instalment"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Instalment</a>
            @endif
            @if(Route::is(['date-first-instalment']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_date_first_instalment"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add First Date Instalment</a>
            @endif
            @if(Route::is(['election-parameters']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_election_para"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Election</a>
            @endif
            @if(Route::is(['card-validity']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_card_validity"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Card Validity</a>
            @endif
            @if(Route::is(['class-call']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_class_call"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Class Call</a>
            @endif
            @if(Route::is(['class-timetable']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_class_timetable"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Class Timetable</a>
            @endif
            @if(Route::is(['subjects']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_subjects"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Subjects</a>
            @endif
            @if(Route::is(['reason-leave']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_reason_leave"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Leaving Reason</a>
            @endif
            @if(Route::is(['class-time-slot']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_class_time_slot"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Class Time Slot</a>
            @endif
            @if(Route::is(['sub-levels']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_sub_level"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Sub Level</a>
            @endif
            @if(Route::is(['class']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_class"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Class</a>
            @endif
            @if(Route::is(['sections']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_section"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Section</a>
            @endif
            @if(Route::is(['files']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_file"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Files</a>
            @endif
            @if(Route::is(['require-files']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_require_file"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Require Files</a>
            @endif
            @if(Route::is(['placement-test']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_placement_test"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Test Subject Type</a>
            @endif
            @if(Route::is(['evaluation-name']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_evaluation_name"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Evaluation Name</a>
            @endif
            @if(Route::is(['weightings']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_weighting"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Weighting</a>
            @endif
            @if(Route::is(['exams-name']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_exams_name"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Name Exams</a>
            @endif
            @if(Route::is(['exams']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_exam"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Exams</a>
            @endif
            @if(Route::is(['annual-average']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_annual_average"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Annual Average</a>
            @endif
            @if(Route::is(['promotion-name']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_promotion_name"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Name Promotion</a>
            @endif
            @if(Route::is(['promotions']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_promotion"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Promotion</a>
            @endif
            @if(Route::is(['name-behaviours']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_name_behaviour"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Name Behaviour</a>
            @endif
            @if(Route::is(['behaviours']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_behaviour"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Behaviour</a>
            @endif
            @if(Route::is(['name-effort-level']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_name_effort"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Name Effort</a>
            @endif
            @if(Route::is(['effort-level']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_effort_level"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Effort Level</a>
            @endif
            @if(Route::is(['name-grading-scale']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_name_grading"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Name Grading</a>
            @endif
            @if(Route::is(['grading-scale']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_grading_scale"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Grading Scale</a>
            @endif
            @if(Route::is(['nursery-theme']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_nursery_theme"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Nursery Theme</a>
            @endif
            @if(Route::is(['nursery-sub-theme']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_nursery_sub"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Nursery Sub</a>
            @endif
            @if(Route::is(['nursery-report']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_nursery_report"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Nursery Report</a>
            @endif
            @if(Route::is(['param-name']))
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_nursery_report"><i class="ti ti-square-rounded-plus-filled me-2"></i>Add Nursery Report</a>
            @endif

        </div>

    </div>
</div>
<!-- /Page Header -->
 
@endif