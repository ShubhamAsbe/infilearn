<?php
// signup_process.php
session_start();
include('dbconnect.php');
include('userDevice.php');


if (isset($_POST['upload_videos_btn'])) {
    if (isset($_POST['board_select'])) {
        if (isset($_POST['class_select'])) {
            if (isset($_POST['subject_select'])) {
                if (isset($_POST['chapter_select'])) {
                    if (isset($_POST['topic_select'])) {

                        $board = $_POST['board_select'];
                        $class = $_POST['class_select'];
                        $subject = $_POST['subject_select'];
                        $chapter = $_POST['chapter_select'];
                        $topic = $_POST['topic_select'];
                        $title = $_POST['video_title'];
                        $description = $_POST['video_desc'];
                        $email = $_SESSION['email'];
                        $userid = $_SESSION['userid'];
                        $author = $_POST['author_name'];

                        $UploadTime = $currentTime;
                        $UserDevice = DeviceCheck();
                        $userBrowser = DeviceCheck() . " Browser Detected";
                        $ipAddress =  Ip_Address();
                        $userDeviceName = $Device_name;
                        $userSystemInfo = $_SERVER['HTTP_USER_AGENT'];


                        $maxsize = 25000000; // 25MB 

                        // This will come in validate function :)

                        if (@ValidateData($board, $class, $subject, $chapter, $topic, $title, $description, $author)) {
                            if (isset($_SESSION['Registered'])) {
                                $_SESSION['usererrors'] = "AlreadyUploadedFile";
                                echo "<script>window.location.replace('adminPanel.php');</script>";
                                // echo "Already Uploaded";
                            } else {
                                if (isset($_FILES['video_file']['name'])) {
                                    $name = $_FILES['video_file']['name'];
                                    $target_dir = "uploadedFiles/";
                                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                    $target_file = $target_dir . $title . "." . $ext;

                                    // Select file type
                                    $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                                    // Valid file extensions
                                    $extensions_arr = array("pdf");

                                    // Check extension
                                    if (in_array($extension, $extensions_arr)) {

                                        // Check file size
                                        if (($_FILES['video_file']['size'] >= $maxsize) || ($_FILES["video_file"]["size"] == 0)) {
                                            // echo "File too large. File must be less than 100MB.";
                                            $_SESSION['usererrors'] = "FiletoolargeFile";
                                            echo "<script>window.location.replace('adminPanel.php');</script>";
                                        } else {
                                            if (move_uploaded_file($_FILES['video_file']['tmp_name'], $target_file)) {

                                                // Insert record

                                                $sql = "INSERT INTO fileuploaded(board,standard,subject,chapter,topic,filepath,title,description,uploadtime,email,isaccepted,views)
                                                            VALUES('$board','$class','$subject','$chapter','$topic','$target_file','$title','$description','$UploadTime','$author','true',0)";

                                                // mysqli_query($con,$query);
                                                // $_SESSION['message'] = "Upload successfully.";
                                                if ($conn->query($sql) === TRUE) {
                                                    // echo "Upload Success";
                                                    $_SESSION['usererrors'] = "UploadSuccessFile";
                                                    $_SESSION['Registered'] = true;
                                                    echo "<script>window.location.replace('adminPanel.php');</script>";

                                                } else {
                                                    // echo "Upload Failed";
                                                    $_SESSION['usererrors'] = "UploadFileFailed";
                                                    echo "<script>window.location.replace('adminPanel.php');</script>";
                                                }
                                            }
                                        }
                                    } else {
                                        // echo "Invalid file extension.";
                                        $_SESSION['usererrors'] = "Invalidfileextension";
                                        echo "<script>window.location.replace('adminPanel.php');</script>";
                                    }
                                } else {
                                    // echo "Please select file";
                                    $_SESSION['usererrors'] = "Pleaseselectfile";
                                    echo "<script>window.location.replace('adminPanel.php');</script>";
                                }
                            }
                        } else {
                            // echo "Validation Error";
                            // $_SESSION['usererrors'] = "ValidationError";
                            echo "<script>window.location.replace('adminPanel.php');</script>";
                        }
                    } else {
                        // echo "Please Select Topic";
                        $_SESSION['usererrors'] = "selecttopic";
                        echo "<script>window.location.replace('adminPanel.php');</script>";
                    }
                } else {
                    // echo "Please Select Chapter";
                    $_SESSION['usererrors'] = "selectchapter";
                    echo "<script>window.location.replace('adminPanel.php');</script>";
                }
            } else {
                // echo "Please Select Subject";
                $_SESSION['usererrors'] = "selectsubject";
                echo "<script>window.location.replace('adminPanel.php');</script>0";
            }
        } else {
            // echo "Please Select Class";
            $_SESSION['usererrors'] = "selectclass";
            echo "<script>window.location.replace('adminPanel.php');</script>1";
        }
    } else {
        // echo "Please Select Board";
        $_SESSION['usererrors'] = "selectboard";
        echo "<script>window.location.replace('adminPanel.php');</script>2";
    }
} else {
    $_SESSION['usererrors'] = "suspecious";
    echo "<script>window.location.replace('adminPanel.php');</script>4";
}



function ValidateData($board, $class, $subject, $chapter, $topic, $title, $description, $author)
{

    $board = $board;
    $class = $class;
    $subject = $subject;
    $chapter = $chapter;
    $topic = $topic;
    $title = $title;
    $description = $description;
    $author =  $author;

    if ($board != "") {
        if ($class != "") {
            if ($subject != "") {
                if ($chapter != "") {
                    if ($topic != "") {
                        if ($title != "" && strlen($title) > 8) {
                            if ($description != "") {
                                if($author != ""){
                                    return true;
                                }else{
                                    return false;
                                }
                            } else {
                                // echo "Description is Blank";
                                return false;
                            }
                        } else {
                            // echo "Please Enter Title";
                            return false;
                        }
                    } else {
                        // echo "Please Select Topic";
                        return false;
                    }
                } else {
                    // echo "Please Select Chapter";
                    return false;
                }
            } else {
                // echo "Please Select Subject";
                return false;
            }
        } else {
            // echo "Please Select Board";
            return false;
        }
    } else {
        // echo "Please Select Board";
        return false;
    }
}
