<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\RapportEpidemie;

class EpidemicReportCreated extends Notification
{
    use Queueable;

    public $report;

    /**
     * تمرير بيانات التقرير عند إنشاء التنبيه
     */
    public function __construct(RapportEpidemie $report)
    {
        $this->report = $report;
    }

    /**
     * نحدد القنوات: 'database' لعرضها في الموقع و 'mail' لإرسال بريد إلكتروني
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * محتوى البريد الإلكتروني (اختياري)
     */
    public function toMail(object $notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('تنبيه صحي: رصد وباء جديد - AgroDz')
            ->line("تم تسجيل حالة مرض جديدة: {$this->report->nom_maladie}")
            ->line("المنطقة المتأثرة: {$this->report->localisation}")
            ->action('عرض تفاصيل التقرير', url('/dashboard'))
            ->line('يرجى اتخاذ الإجراءات الوقائية اللازمة.');
    }

    /**
     * البيانات التي ستخزن في جدول الـ notifications في قاعدة البيانات
     */
    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'title' => 'تحذير صحي جديد',
            'message' => "تم رصد مرض {$this->report->nom_maladie} في {$this->report->localisation}",
            'animal' => $this->report->type_animal,
            'cases' => $this->report->nombre_cas,
        ];
    }
}