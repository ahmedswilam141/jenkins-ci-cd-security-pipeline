<?php

namespace App;

class SubscriptionManager
{
    public function getDaysRemaining($totalDays, $daysUsed)
    {
        $remainingDays = $totalDays - $daysUsed;

        if ($remainingDays < 0) {
            return 0;
        }

        return $remainingDays;
    }
}
