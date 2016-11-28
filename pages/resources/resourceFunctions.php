<?php

function getResourceQuery($model, $alias, $serial, $inventory, $hwType, $campus, $reference){
    include_once("../../inc/MySQLConnection.php");

    $strQuery = "select recursos.*, ubicaciones.UB_CAMPUS 
                  from recursos, ubicaciones, recursos_referencias 
                  WHERE recursos.RE_LOCATION = ubicaciones.UB_ID AND recursos.RE_ID = recursos_referencias.RR_RESOURCEID";
    
    if(!empty($model)){
        $strQuery = $strQuery . " AND recursos.RE_MODEL LIKE '%$model%'";
    }

    if(!empty($alias)){
        $strQuery = $strQuery . " AND recursos.RE_ALIAS LIKE '%$alias%'";
    }

    if(!empty($serial)){
        $strQuery = $strQuery . " AND recursos.RE_SERIAL LIKE '%$serial%'";
    }

    if(!empty($inventory)){
        $strQuery = $strQuery . " AND recursos.RE_INVENTORY LIKE '%$inventory%'";
    }

    if(!empty($hwType)){
        $strQuery = $strQuery . " AND recursos.RE_HWTYPE LIKE '%$hwType%'";
    }

    if(!empty($campus)){
        $strQuery = $strQuery . " AND ubicaciones.UB_CAMPUS LIKE '%$campus%'";
    }

    if(!empty($reference)){
        $strQuery = $strQuery . " AND recrusros_referencias.RR_REFERENCEID = $reference";
    }
    
    $strQuery = $strQuery . " order by recursos.RE_CREATED desc";

    $sql = mysqli_query($connection, $strQuery);

    if(mysqli_num_rows($sql) == 1){
        //$data = mysqli_fetch_assoc($sql);
        //header("Location: specificCharter.php?charter=".$data['CE_CVE']."&date=".$data['CE_FEC']);
        //exit;
    } else {
        return $sql;
    }
}

function getLocationQuery($campus, $pile, $floor, $room){
    include_once("../../inc/MySQLConnection.php");

    $strQuery = 'select * from ubicaciones';
    
    $flag = false;
    
    if(!empty($campus)){
        $strQuery = $strQuery . " where UB_CAMPUS LIKE '%$campus%'";
        $flag = true;
    }

    if(!empty($pile)){
        if ($flag)
            $strQuery = $strQuery . " AND";
        else {
            $flag = true;
            $strQuery = $strQuery . " where";
        }

        $strQuery = $strQuery . " UB_PILE LIKE '%$pile%'";
    }

    if(!empty($floor)){
        if ($flag)
            $strQuery = $strQuery . " AND";
        else {
            $flag = true;
            $strQuery = $strQuery . " where";
        }

        $strQuery = $strQuery . " UB_FLOOR LIKE '%$floor%'";
    }

    if(!empty($room)){
        if ($flag)
            $strQuery = $strQuery . " AND";
        else {
            $flag = true;
            $strQuery = $strQuery . " where";
        }

        $strQuery = $strQuery . " UB_ROOM LIKE '%$room%'";
    }
    
    $strQuery = $strQuery . " order by UB_CAMPUS";

    $sql = mysqli_query($connection, $strQuery);

    return $sql;
}

function getAreaQuery($name, $campus, $reference){
    include_once("../../inc/MySQLConnection.php");

    $strQuery = 'select * from areas';

    $flag = false;

    if(!empty($campus)){
        $strQuery = $strQuery . " where AR_CAMPUS LIKE '%$campus%'";
        $flag = true;
    }

    if(!empty($name)){
        if ($flag)
            $strQuery = $strQuery . " AND";
        else {
            $flag = true;
            $strQuery = $strQuery . " where";
        }

        $strQuery = $strQuery . " AR_NAME LIKE '%$name%'";
    }

    if(!empty($reference)){
        if ($flag)
            $strQuery = $strQuery . " AND";
        else {
            $flag = true;
            $strQuery = $strQuery . " where";
        }

        $strQuery = $strQuery . " AR_TYPE = $reference";
    }

    $strQuery = $strQuery . " order by AR_CAMPUS";

    $sql = mysqli_query($connection, $strQuery);

    return $sql;
}