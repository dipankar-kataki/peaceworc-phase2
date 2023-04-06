<?php

namespace App\Common;

Class JobStatus{
    const NotPosted = 0;
    const Open = 1;
    const OnGoing = 2;
    const Completed = 3;
    const Closed = 4;
    const PendingForApproval = 5;
    const BiddingStarted = 6;
    const BiddingEnded = 7;
    const QuickCall = 8;
    const OnHold = 9;
    const JobAccepted = 10;
    const JobCancelled = 11;
    const JobExpired = 12;
    const JobDeleted = 13;
}