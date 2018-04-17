<?php
if ($argc == 1) {
    echo "Run code as 'php strEval.php arg1 arg2 such that argK is a string like 2*3+1\n";
} else {
    for ($i = 1; $i < count($argv); ++$i) {
        echo $argv[$i] . " = " . strEval($argv[$i]) . "\n";
    }
}

function strEval($sExpr)
{
    $aExpr = str_split($sExpr);

    $aOpr = array('-', '+', '/', '*', '^', '(', ')');
    $aOprPrec = array('-'=>0, '+'=>0, '/'=>1, '*'=>1, '^'=>2);

    $aRpn = array();

    $aPostfToInf = array();
    $nPosToInInde = 0;

    for($i=0; $i<count($aExpr); ++$i) 
    {
        switch ($aExpr[$i]) {
            case '(':
                $aPostfToInf[$nPosToInInde] = '(';
                ++$nPosToInInde;
                break;
            case ')':
                while ($aPostfToInf[$nPosToInInde-1] != '(') {
                    $aRpn[] = $aPostfToInf[$nPosToInInde-1];
                    --$nPosToInInde;
                }
                --$nPosToInInde;
                break;
            case '+':
            case '-':
            case '*':
            case '/':
            case '^':
                if ($nPosToInInde == 0) {
                    $aPostfToInf[0] = $aExpr[$i] ;
                    ++$nPosToInInde;
                } else 
                {
                    while (($nPosToInInde != 0) && ($aPostfToInf[$nPosToInInde-1] != '(')) {
                        if ($aOprPrec[$aExpr[$i]] <= $aOprPrec[$aPostfToInf[$nPosToInInde-1]]) {
                            $aRpn[] = $aPostfToInf[$nPosToInInde-1];
                            --$nPosToInInde;
                        } else {
                            break;
                        }
                    }

                    ++$nPosToInInde;
                    $aPostfToInf[$nPosToInInde-1] = $aExpr[$i];
                }
                break;
            default:
                $aRpn[] = $aExpr[$i];
        }
    }

    while ($nPosToInInde) {
        $aRpn[] = $aPostfToInf[$nPosToInInde-1];
        --$nPosToInInde;
    }

    $aPosfEval = array();
    $nPosfEval = 0;
    for ($i=0; $i<count($aRpn); ++$i) {
        switch ($aRpn[$i]) {
            case '+':
            case '-':
            case '*':
            case '/':
            case '^':
                $aPosfEval[$nPosfEval - 2] = doOperator($aPosfEval[$nPosfEval-2], $aPosfEval[$nPosfEval-1], $aRpn[$i]);
                --$nPosfEval;
                break;
            default:
                $aPosfEval[$nPosfEval] = $aRpn[$i];
                ++$nPosfEval;
        }
    }

    return $aPosfEval[0];
}

function doOperator($a, $b, $opr)
{
    switch ($opr) {
        case '+':
            return ((float)$a + (float)$b);
        case '-':
            return ((float)$a - (float)$b);
        case '*':
            return ((float)$a * (float)$b);
        case '/':
            return ((float)$a / (float)$b);
        case '^':
            return pow((float)$a, (float)$b);
    }
}
