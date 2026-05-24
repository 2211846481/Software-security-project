<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agreement;
use App\Models\User;
use Carbon\Carbon;
use App\Mail\AgreementExpiryNotification;
use Illuminate\Support\Facades\Mail;

class CheckAgreementsExpiry extends Command
{
    protected $signature = 'agreements:check-expiry';
    protected $description = 'Checks for agreements expiring soon and sends notifications via email.';

    public function handle()
    {
        $expiryDates = [
            Carbon::now()->addDays(90)->format('Y-m-d'),
            Carbon::now()->addDays(60)->format('Y-m-d'),
            Carbon::now()->addDays(30)->format('Y-m-d'),
        ];
        
        $agreementsToExpire = Agreement::with('department.users')
            ->whereIn('expiry_date', $expiryDates)
            ->get();
        
        if ($agreementsToExpire->isEmpty()) {
            $this->info('No agreements are expiring soon.');
            return 0;
        }

        // 🟢 قائمة فارغة لتخزين جميع المستخدمين الذين سيتم إرسال الإشعار لهم، مع تجنب التكرار
        $usersToNotify = collect();

        foreach ($agreementsToExpire as $agreement) {
            // 🟢 جلب جميع مستخدمي القسم المسؤول عن الاتفاقية
            $departmentUsers = $agreement->department->users;
            
            // 🟢 جلب جميع المستخدمين الذين يملكون دور "Admin"
            $admins = User::where('role', 2)->get();
            
            // 🟢 دمج القائمتين وإزالة التكرار
            $usersToNotify = $usersToNotify->merge($departmentUsers)->merge($admins);
        }
        
        // 🟢 إزالة التكرار من القائمة النهائية
        $usersToNotify = $usersToNotify->unique('id');

        // 🟢 الآن، أرسل الإشعار مرة واحدة فقط لكل مستخدم في القائمة
        foreach ($usersToNotify as $user) {
            // يمكنك الآن تعديل محتوى الرسالة ليشمل جميع الاتفاقيات التي تخصه
            // ولكن الآن، سنرسل رسالة عامة
            Mail::to($user->email)->send(new AgreementExpiryNotification($agreementsToExpire->first())); // مثال: أرسل تفاصيل أول اتفاقية
            $this->info("Notification sent to user: {$user->name} for expiring agreements.");
        }
        
        $this->info('Expiry check completed successfully!');
        
        return 0;
    }
}