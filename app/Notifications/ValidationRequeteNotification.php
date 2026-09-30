<?php

namespace App\Notifications;

use App\Models\Requete;
// use App\Models\Requette;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ValidationRequeteNotification extends Notification
{
    use Queueable;

    public $requete;

    public function __construct(Requete $requete)
    {
        $this->requete = $requete;
    }

    // public function via($notifiable)
    // {
    //     return ['database', 'mail']; // Notification DB + Email
    // }

    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //         ->subject('Nouvelle requête à valider')
    //         ->line('Une nouvelle requête nécessite votre validation.')
    //         ->line('Demandeur: ' . $this->requete->demandeur)
    //         ->line('Service: ' . $this->requete->service)
    //         ->line('Montant: ' . number_format($this->requete->montant_fcfa, 2, ',', ' ') . ' F CFA')
    //         ->action('Voir la requête', url('/requetes/' . $this->requete->id . '/validation'))
    //         ->line('Merci de valider cette requête dans les plus brefs délais.');
    // }

    // public function toDatabase($notifiable)
    // {
    //     return [
    //         'requete_id' => $this->requete->id,
    //         'demandeur' => $this->requete->demandeur,
    //         'service' => $this->requete->service,
    //         'montant' => $this->requete->montant_fcfa,
    //         'message' => 'Nouvelle requête à valider de ' . $this->requete->demandeur,
    //         'url' => '/requetes/' . $this->requete->id . '/validation',
    //         'type' => 'validation_requete'
    //     ];
    // }


    public function via($notifiable)
    {
        return ['database']; // Vous pouvez ajouter 'mail' si nécessaire
    }

    public function toDatabase($notifiable)
    {
        return [
            'requete_id' => $this->requete->id,
            'numero' => $this->requete->numero,
            'demandeur' => $this->requete->demandeur,
            'montant' => $this->requete->montant_fcfa,
            'message' => 'Nouvelle requête nécessite votre validation',
            'statut' => $this->requete->statut,
            'url' => route('requetes.show', $this->requete->id),
            'created_at' => now(),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nouvelle requête à valider')
                    ->greeting('Bonjour ' . $notifiable->name)
                    ->line('Une nouvelle requête nécessite votre validation.')
                    ->line('Numéro: ' . $this->requete->numero)
                    ->line('Demandeur: ' . $this->requete->demandeur)
                    ->line('Montant: ' . number_format($this->requete->montant_fcfa, 0, ',', ' ') . ' FCFA')
                    ->action('Voir la requête', route('requetes.show', $this->requete->id))
                    ->line('Merci d\'utiliser notre application!');
    }

    public function toArray($notifiable)
    {
        return [
            'requete_id' => $this->requete->id,
            'message' => 'Nouvelle requête à valider',
        ];
    }
}