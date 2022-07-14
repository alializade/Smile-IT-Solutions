<?php

namespace AliAlizade\Transfer\Enums;

enum TransactionStatusEnum: string
{
    case SUCCESS = 'SUCCESS';
    case FAILED = 'FAILED';
    case PENDING = 'PENDING';
    case CANCELED = 'CANCELED';
}