<?php

class Polyline
{
    /**
     * @var int $precision
     */
    //protected static $precision = 5;
    protected static $precision = 6;
    
    /**
     *
     * @param array $points List of points to encode. Can be a list of tuples,
     *                      or a flat, one-dimensional array.
     * @return string encoded string
     */
    final public static function encode()
    {
        $points = self::flatten($points = array(
            array(41.89084,-87.62386),
            array(41.89086,-87.62279),
            array(41.89028,-87.62277),
            array(41.89028,-87.62385),
            array(41.89084,-87.62386)
        ));
        $encodedString = '';
        $index = 0;
        $previous = array(0,0);
        foreach ( $points as $number ) {
            $number = (float)($number);
            $number = (int)round($number * pow(10, static::$precision));
            $diff = $number - $previous[$index % 2];
            $previous[$index % 2] = $number;
            $number = $diff;
            $index++;
            $number = ($number < 0) ? ~($number << 1) : ($number << 1);
            $chunk = '';
            while ( $number >= 0x20 ) {
                $chunk .= chr((0x20 | ($number & 0x1f)) + 63);
                $number >>= 5;
            }
            $chunk .= chr($number + 63);
            $encodedString .= $chunk;
        }
        return $encodedString;
    }
    /**
     * @param string $string Encoded string to extract points from.
     *
     * @return array points
     */
    final public static function decode()
    {
        $result = array();
        $string = "ooynu@whrnrCqL|LkUbWgr@~w@cCuCf[e_@|UcWzpAevAhDkDnOwQ|KaO`FaGzDkFlHaKrIwObCuErq@ypAv@kBtZqd@vu@ypA`]qj@rImNjtAuyBl\qh@pBkD~RyYfGaKjUe_@v@aK~tAuyBnEmLe@cS_`@}k@_IwKkAuAqLmRd@aEfGTbM`Mnc@fo@nOn\`FlNlHxUnEvMzNze@xTda@vM|m@tFbUzNrl@bWj_AvmB|sGdJxWdThq@tPd]~UxUlf@jFrI?p`@kBd@_CbC_CtF?tCtCd@~BQjFnc@kDvMi@jbCshDz|FqnG|lC}}Cpw@miA|v@oqApdMg}Uxu@wjAxkFk~IfgBemCplBurDzqB}zEho@meAzhCkoDtd@uz@vwBugFxu@mkAjtKoqLvdColCzsAclAf~BsiBljAezA|Xqj@~_@wbAvpBqhGx{Fk{RbZafAbrD}|LlbC_bHhe@adAl_@gm@rlBmhCdoDs{EhxFaeJn`DcdE|}D{eGdjFqjG`~@wbAjqBijBpc@{e@r`@}i@h}HojNjy@oqAtPcSx{CymCzbDm}Dn`DulDdrD{lEhaCw{Bj_DciCjvDwcChsAu`A~l@sr@|Xgg@l_@adAjUytAzDiy@kAux@iDge@mRafAm_@wdAqV{e@cd@gm@woAijBag@sn@sj@_v@ebAcrAop@qb@ie@wKg^~@yu@vOcPuAkUwKcMaOyGySkAmRv@wOtFmT`ScY`vB{~A`{DgyCncDccCphBmgAfw`@slRflAge@rjDwfA`]kJvbMwtDrnEijBhb\qpNjfMwqFtjDyrAfjC_t@j}ZiwGjyMesCjuHq_Bfye@}cKdqIsiBteBo^n|MqtCt{OiiDxeAcYd{TqsEdd@aKjxNq|CfhPujDj}B{e@d_MeoCd}D_x@xoBoXt|CoX~tCwIvjPaGvuBi@djEtGh}C_A|fB_AtqAU~gIkFzxLtCl|Lh@dnGi@ttA_CjpCjD||b@UlXTb`@Th`KvI~|AtCtuCbQrmAvMndDfi@lUjDzhA`OdbCvOpdC~BncBuEduBcUzeByWdrCsl@pgC{a@jz[azDhlCcYtyNkcAno@aEpnEo\d{BmN~mDyUlwWa}BhoCuEbtBtCtkDvO`VTpOTfd@i@`c@`Glr@lLvgAvQjfDzg@~_BbQfvF|m@j^`Ex|ZrdDp`CnVh{A`If_EjHddBkBn`DaMft@uG``AkJlyCge@hlEmeAnvBis@llBiu@ddBiy@vtBmmAzaAsp@nlBe|AroKghIx|GkpFxaCgdBllCexA`pAqj@jhB}o@nr@wQppCqj@zhDqb@~eBuGllDuA~uBtA|}JtEdtCkBhxCmPrsByWdjAySxhGcpAppFcnA`v@ySddCu|@rsBkaA`v@ec@h~AmeAl|DqzCpvCghBvgCclAv}Asl@`cCis@xtC}m@`zE}i@noCwK`~Oo`@vtGmTxjA_ApyCcSxwBoZfgBe_@jnAe]riBsn@|nBu|@nuAiw@luCsiBhqDmfCpvKkiHxjA_t@b`B_z@nHaEv`Ck_AjkCuz@zdA{a@`fBk{@ztBetAj{Dg}Cd`CoyA|um@om^lnAgo@dsE_qBbvAsv@roBcrAprAwbArlC_mB|dEciCfmBouAd`BowAf}BmhCjdCwxDl~@}hB|d@wfAl^kaAjaA}cDbS}q@`uLana@x}@eqC|t@}dB~nAccCl~Aa_CdcB_sBvtZgl[|w@is@x`Asv@xpAk{@vfBabAhzBkaA`lAec@hvHa_CzsDiy@toBoXfm@kFjwCmNxcBi@fvCtGtoCnVnvv@dvL`mLfhBjyf@nwHxyAlNf|C`MdeEaEziCySz`AwMteByY~zA{_@dqHw}Bp`k@wgQr_@aMnaa@idM`~Bsp@pUuGjiD}o@t{BoVj`BwI|`BuCfoCtCvSh@fts@hrBfpH`OfiA_Afv@Udi@jDz]~Bxv@jHxnDlP`qBtE`wCuCxeCcQljAaO~|w@gsNjhYueF`O_CtfY_eFrzBmT`kAkF|_CkDjhDvI~dRp_BnhEz_@tr@jDzxBjBh_BuCzbBwInlCe[dbAcSpcCgo@h|Asl@fbBsv@jyA_|@|eCseBnyBcpAzbBiu@`zBiu@vo@wOjTkFpgGwfAvsDafApfCwdApgAsl@zuGimDrbEa{BdkGieDznb@w}Tp}Ais@htCu|@|lAyY~oBe]jhCoXbwBkJzwCUdkKzc@ldjA`mFhhB`Ep|Bi@vsCwOd\kDftByYt|Csp@nlBgm@|aCwdAf~o@gc]hkBclAlcAiw@r}AoyAnyAqcBn}@cpAbx@osAh_AinBth@mmAxo@soBjdCiiK||@onCpz@soBh|@qcBfeA{`BncAoqAbsBktBteD}{ChtBq{AvjBmgAfdBux@zwBiw@vaIkvB~hFytA`pIgfBx`Csr@bwA}i@~dCmoAtvBosAn_BoqAbzAezAxgBuwBhwB_jDhc@_z@r}DirI~}JuzRd}EmhJn{BgaD`gVueXfhAowAdqAktBp}@}hBrw@_mBj]wfA~y@}eDnj@_lDfPghBnNsfDjA{yCuOgnIubCydtAiJyyFSg`BvFq~CvFmeAlWqxCng@igDtk@ykC`n@smBp`AcgCp}@uqBnaJ{vSd~@aaCfr@uwBbh@w}Bp^a}Br[aoF~A_z@|G{|AbLcpA|f@quEhMuwBxpBqb]lTijB~Wq_Bj_AehEdYkaAnm@q}Afo@ypAng@k{@~]gg@~uA{`BjrD{sCzjB{`BlrA{zAfjBccCbgA{|AnlAilBn{Ee`I`pA}dB`mAetAhkAwdArsAwbAjjIifFd~@sv@|i@}k@ng@}o@jbAqcBzr@gfBxwA{jEnlAygCtjAsmBzqAgdBluA{zApjPwvOrw@st@|JaKnKmL~kTetSlzBa_CtpAqcBtn@kaAhnAkzBvdD{fIj_Aw_Cx_@kaA~{AsfD|c@{g@fYmTj_Age@jf@aKr}@wQ`UmPjGkHvCwIpBaK?mRkAaIkDwK}DaGwIwIiJkDoNkBcITiJtAwF`E}G`GkGlNkGrl@tUv{Bd\tpD~]~gDvh@`vDbh@pvC|Mfm@~c@~lBbk@hpBzP|m@jGlTfShu@~bAv|DzPfo@nQ|o@jGbWbIx[bFtC|Jpd@|Gd]kA`GdC`MlZtuB|i@zwCvCbUjA~w@iJjcAuL`~@mWtqBiJp{AqEj|BhJhbFvF|m@pBvIzVj_Ar[hs@`Xza@jcCnnCx\fg@f\|q@fYj}@~Zp_BzPlhCd@`G`q@|gKnQblAve@xrAr{Bn~F~`@`dAlwBltFjeAnjCpcAzuCfV``AdF|o@?d_@qHj{@mZjcAs`Aj|BcLd_@cO|o@_[dzAwk@veCcIx[SlRch@~rBiPfm@oKfm@uRhjBkDi@}JnuAg\`zDiPxoC}J`uFwFrtGqBfm@pETwCd_@uRtx@g\nyAwk@jtBgS|q@iMpb@qBtG`yAfi@jdBxWj`@lLvCi@dFtAhMjHx@jDr[aMhJkFrXwOnKaGpd@yWhMaItRuCx{@uA|l@_AfS?jDTxb@xYjf@nVjhAda@l]lLzSjHpB~@|kApf@~c@`O~]`MvC~@xb@lLzx@lL~AjB|JtA|JtAdC_A|pBz]`XtAnvCmR|f@uEtt@?np@h@|kAh@`z@mLl_AkJbn@jH|l@bUjbAuAji@aOdCuAde@yYfYqh@pEmLjDyWeCa`A_B{a@dCmTjGcQjGwKhMaG`w@oXtRaG~c@mNdFuCr[y[`X{_@x@uCvC{]vCuEjf@mNvLaKnNkFnQU`[jDrXjDx_@jBjkAkFpd@wM`UwInKuEhMaIdb@wKdCU|Jh@d_@`GnN~BvI~@tRtC~`@`GzP~Bl`@jFhM~@zYjB|MTdb@h@r[uCbOkDve@uCl]~@x@uA~Ai@vCTpBjDSjDkA~BqBj_AjAxWjAvKhMzg@hPn^vCvIjD`M|Gn`@pEblARlL~AtAR~By@~BRlRjAhq@~A~s@jA|m@jArp@x@bSvCza@bLfo@x@~BvInVhl@j_Avk@hu@lZza@fVx[|GjJtt@`fAbLz]pHxWjD?jAtAx@`G?jBeC`EwFrl@SjFdb@tEx\jFrXtCjeAlL`UtCzYjD|Dqf@sXaEa}@qI";
        $string = "o`y{nAfjccfDg@{aAfc@g@?nbA_b@R";
        $points = array();
        $index = $i = 0;
        $previous = array(0,0);
        while ($i < strlen($string)) {
            $shift = $result = 0x00;
            do {
                $bit = ord(substr($string, $i++)) - 63;
                $result |= ($bit & 0x1f) << $shift;
                $shift += 5;
            } while ($bit >= 0x20);
            $diff = ($result & 1) ? ~($result >> 1) : ($result >> 1);
            $number = $previous[$index % 2] + $diff;
            $previous[$index % 2] = $number;
            $index++;
            $points[] = $number * 1 / pow(10, static::$precision);

        }
        return $points;
    }
    /**
     * Reduce multi-dimensional to single list
     *
     * @param array $array Subject array to flatten.
     *
     * @return array flattened
     */
    final public static function flatten( $array )
    {
        $flatten = array();
        array_walk_recursive(
            $array, // @codeCoverageIgnore
            function ($current) use (&$flatten) {
                $flatten[] = $current;
            }
        );
        return $flatten;
    }
    /**
     * Concat list into pairs of points
     *
     * @param array $list One-dimensional array to segment into list of tuples.
     *
     * @return array pairs
     */
    final public static function pair( $list )
    {
        return is_array($list) ? array_chunk($list, 2) : array();
    }
}
