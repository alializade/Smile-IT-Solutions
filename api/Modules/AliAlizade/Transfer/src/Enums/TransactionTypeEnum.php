<?php

enum TransactionTypeEnum
{
    case INITIAL_DEPOSIT;
    case INTERNAL_TRANSFER;
    case DEPOSIT;
    case WITHDRAW;
}