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
    const QuickCall = 7;
    const OnHold = 8;
}