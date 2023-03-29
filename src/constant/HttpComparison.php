<?php

namespace Lit\DevOps\constant;

use Lit\Utils\LiConst;

/**
 * @value("EQ","等于")
 * @value("GT","大于")
 * @value("LT","小于")
 * @value("GE","大于等于")
 * @value("LE","小于等于")
 * @value("IN","在...内")
 * @value("NOT_IN","不在...内")
 */
class HttpComparison extends LiConst
{
    const EQ = "=";
    const GT = ">";
    const LT = "<";
    const GE = ">=";
    const LE = "<=";
    const IN = "in";
    const NOT_IN = "not in";
}