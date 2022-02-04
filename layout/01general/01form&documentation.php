<?php
    $app = strtolower($appName);
    if($app == "gaia"){
        $G_DepartmentId = $_GET["department"];
        echo "<script> var G_DepartmentId = ".$G_DepartmentId.";</script>";
        $rootFolder = "";
    }
    else{
        $rootFolder = "../../../gaia/";
        echo "<script> var G_DepartmentId = ".$user["GaiaDepartmentId"].";</script>";
    }
        $connect_gaia = connect_sql_server("gaia");

        $Departments = array();
        $query = " SELECT [File].[Id]
                    ,[File].[GroupId]
                    ,[FileGroup].[Name][Group]
                    ,[File].[FolderPath]
                    ,[File].[FileName]
                    ,[File].[Title]
                    ,[File].[Description]
                    ,[FileGroup].[DepartmentId]
                    ,[Department].[Name][Department]
                    FROM [Gaia].[dbo].[FACT_UniversalFile][File]
                    LEFT OUTER JOIN [Gaia].[dbo].[DIM_UniversalFileGroup][FileGroup]
                    ON [File].[GroupId] = [FileGroup].[Id]
                    LEFT OUTER JOIN [Gaia].[dbo].[DIM_Department][Department]
                    ON [FileGroup].[DepartmentId] = [Department].[Id]
                    WHERE [File].[IsEnable] = 1
                    AND [FileGroup].[IsEnable] = 1
                    ORDER BY [File].[Order] ,[FileGroup].[Order] ;";
        $result = sqlsrv_query($connect_gaia, $query, array(), array("Scrollable"=>"buffered"));
        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
        {
            $FileNameExplode = explode(".",$row["FileName"]);//[0] = 20190722_135921_hrd ; [1] = xlsx
            $FileNameCount = count($FileNameExplode);//2
            $ExtensionOrder = $FileNameCount - 1;//1

            $Extension = $FileNameExplode[$ExtensionOrder];
            $Extension = strtolower($Extension);

            $IconFA = "far fa-file";
            if($Extension == "txt")$IconFA = "far file-alt dark_grey";
            else if($Extension == "xls" || $Extension == "xlsx")$IconFA = "far fa-file-excel retro_green";
            else if($Extension == "csv")$IconFA = "fas fa-file-csv retro_green";
            else if($Extension == "doc" || $Extension == "docx")$IconFA = "far fa-file-word magenta";
            else if($Extension == "ppt" || $Extension == "pptx")$IconFA = "far fa-file-powerpoint retro_orange";
            else if($Extension == "pdf")$IconFA = "fas fa-file-pdf retro_red";
            else if($Extension == "jpg" || $Extension == "jpeg" || $Extension == "gif" || $Extension == "png" || $Extension == "bmp")$IconFA = "far fa-file-image blue";
            else if($Extension == "wmv" || $Extension == "avi" || $Extension == "mpeg" || $Extension == "3gp" || $Extension == "mp4" || $Extension == "mkv")$IconFA = "far fa-file-video retro_lightblue";
            else if($Extension == "wma" || $Extension == "mp3" || $Extension == "wav")$IconFA = "far fa-file-audio retro_lightblue";
            else if($Extension == "zip" || $Extension == "rar" || $Extension == "7z" || $Extension == "7zip")$IconFA = "far fa-file-archive purple";

            $row["IconFA"] = $IconFA;

            $Departments[$row["Department"]][$row["Group"]][] = $row;

            //echo $IconFA;
        }
?>

<head>
    <style>
    .collapsible {
        background-color: grey;
        color: white;
        cursor: pointer;
        padding: 8px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        margin-top:10px;
    }

    .active, .collapsible:hover {
        background-color: #18775e;
    }

    .content {
        background-color: #73b19f;
        padding: 0 18px;
        color: black;
        display: none;
        overflow: show;
        background-color: #f1f1f1;
    }
    </style>
</head>
<body>
<div style="margin:20px;">
    <?php
     foreach ($Departments as $key => $Department) {
    ?>
        <div class="float_left" style="width:50%;">
            <div style="padding: 5px;">
                <?php echo "<button class='collapsible'><h4>".$key."</h4></button>";
                ?>
                    <div class="content">
                        <div style="padding:5px;width:100%;max-height:200px;overflow-y:auto;">
                        <?php
                        foreach ($Department as $GroupName => $group){
                            echo "<ul><button class='collapsible'><i><h6>".$GroupName."</h6></i></button>"; ?>
                                <div class="content">
                                <!-- <div style="padding:5px;width:100%;max-height:100px;overflow-y:auto;"> -->
                                    <?php
                                    foreach ($group as $key => $file)
                                    {
                                        ?>
                                        <li style="background-color:lightgrey; border:2px white; padding:5px; margin: 5px;">

                                        <!-- Print -->
                                        <!-- <div id="divToPrint" style="display:none;">
                                            <?php echo $rootFolder.$file["FolderPath"].$file["FileName"];?>
                                        </div>
                                        <a onClick="printFormDocumentConfirm();"><i class="fa fa-print fa-2x"></i></a>
                                        <script type="text/javascript">
                                            function printFormDocumentConfirm() {
                                            var divToPrint = document.getElementById('divToPrint');
                                            var Win = window.open('<?php echo $rootFolder.$file["FolderPath"].$file["FileName"]; ?>','<?php echo $file["Title"];?>', 'width=1000px,height=700px');
                                            Win.document.open();
                                            Win.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
                                            Win.document.close();
                                            }
                                        </script> -->

                                        <!-- Download -->
                                        <p title="<?php echo $file["Description"];?>">
                                            <a href="<?php echo $rootFolder.$file["FolderPath"].$file["FileName"]; ?>" download>
                                                <i class="<?php echo $file["IconFA"];?> fa-2x"></i> <?php echo $file["Title"];?></a></p>

                                    <!-- <p class='left' title='".$row["Description"]."'>
                                        <a href='".$row["FolderPath"].$row["FileName"]."' download>
                                            <span class = 'k-button' onclick = 'manageFileEditDocumentFilePath(".$row['Id'].");'>
                                            <i class = '".$IconFA."'></i></span></a> ".$row['Title']."</p> -->
                                        </li>
                                    <?php
                                    }
                                        ?>
                                <!-- </div> -->
                                </div>
                            <?php echo "</ul>";
                        }
                            ?>
                        </div>
                    </div>
            </div>
        </div>
    <?php
    }
    ?>
        <div class='clear_both'></div>

    <script>
        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
            content.style.display = "none";
            } else {
            content.style.display = "block";
            }
        });
        }
    </script>
</div>
</body>
