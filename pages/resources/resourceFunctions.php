<?php

function getResourceQuery($model, $alias, $serial, $inventory, $hwType, $campus, $reference){
    include("../../inc/MySQLConnection.php");

    $strQuery = "select recursos.*, ubicaciones.UB_CAMPUS from recursos, ubicaciones, recursos_referencias";
    
    $flag = false;
    
    if(!empty($model)){
        $strQuery = $strQuery . " where recursos.RE_MODEL = '$model'";
        $flag = true;
    }

    if(!empty($charterYear)){
        if ($flag)
            $strQuery = $strQuery . " AND";
        else {
            $flag = true;
            $strQuery = $strQuery . " where";
        }

        $strQuery = $strQuery . " YEAR(CE_FEC) = '$charterYear'";
    }

    if(!empty($idContract)){
        if ($flag)
            $strQuery = $strQuery . " AND";
        else {
            $flag = true;
            $strQuery = $strQuery . " where";
        }

        $strQuery = $strQuery . " CE_CON = '$idContract'";
    }

    if(!empty($idCarrier)){
        if ($flag)
            $strQuery = $strQuery . " AND";
        else {
            $flag = true;
            $strQuery = $strQuery . " where";
        }

        $strQuery = $strQuery . " CE_TRA = '$idCarrier'";
    }

    if($alreadySigned){
        if ($flag)
            $strQuery = $strQuery . " AND";
        else {
            $flag = true;
            $strQuery = $strQuery . " where";
        }

        $strQuery = $strQuery . " CE_SGN = '$alreadySigned'";
    }
    
    $strQuery = $strQuery . " order by CE_FEC desc, CE_CVE desc limit $limit";

    $sql = mysqli_query($connection, $strQuery);

    if(mysqli_num_rows($sql) == 1){
        $charterData = mysqli_fetch_assoc($sql);
        header("Location: specificCharter.php?charter=".$charterData['CE_CVE']."&date=".$charterData['CE_FEC']);
        exit;
    } else {
        return $sql;
    }
}

function getPriceIndexQuery($month, $year, $limit){
    include("../../inc/MySQLConnection.php");

    $strQuery = 'select * from Indices';
    
    $flag = false;
    
    if(!empty($month)){
        $strQuery = $strQuery . " where MONTH(IN_FEC) = '$month'";
        $flag = true;
    }

    if(!empty($year)){
        if ($flag)
            $strQuery = $strQuery . " AND";
        else {
            $flag = true;
            $strQuery = $strQuery . " where";
        }

        $strQuery = $strQuery . " YEAR(IN_FEC) = '$year'";
    }
    
    $strQuery = $strQuery . " order by IN_FEC desc limit $limit";

    $sql = mysqli_query($connection, $strQuery);

    return $sql;
}