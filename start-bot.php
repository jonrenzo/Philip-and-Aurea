<?php
function startPythonScript() {
    $pythonExe = "chatbot-deployment/venv/Scripts/python.exe";
    $pythonScript = "chatbot-deployment/app.py";

    $processName = basename($pythonScript);
    exec("tasklist | findstr python.exe", $output);

    if (empty($output)) {
        $command = "$pythonExe $pythonScript > NUL 2>&1";
        $handle = popen("start /B ". $command, "r");

        if ($handle !== false) {
            pclose($handle);
            return "Python script started successfully";
        } else {
            return "Failed to start Python script";
        }
    } else {
        return "Python script is already running";
    }
}

$status = startPythonScript();
?>