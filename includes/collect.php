<?php
    function getList($url,$ulname){
        $str = @file_get_contents($url);
        $listReg = "/<ul class=\"$ulname\">\s*<li.*>(.*)<\/li>\s*<\/ul>/imsU";
        preg_match($listReg,$str,$res);

        return $res;
    }

    function getContent($url,$contentname){
        $str = @file_get_contents($url);
        $contentReg = "/<div\s*class=\"$contentname\"\s*.*>(.*)<\/div>/imsU";
        preg_match($contentReg,$str,$text);

        return $text;
    }