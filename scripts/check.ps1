param([switch]$CheckDatabase)

$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$phpCommand = Get-Command php -ErrorAction SilentlyContinue
$phpBinary = if ($phpCommand) { $phpCommand.Source } else { 'C:\xampp\php\php.exe' }
Push-Location $projectRoot
try {
    $files = @(Get-ChildItem app, config, views, scripts, tests -Filter '*.php' -File -Recurse)
    $files += Get-Item index.php
    foreach ($file in $files) {
        $result = & $phpBinary -l $file.FullName 2>&1
        if ($LASTEXITCODE -ne 0) { throw ($result -join "`n") }
    }
    Write-Output "PHP syntax passed: $($files.Count) files"
    foreach ($file in (Get-ChildItem assets/js -Filter '*.js' -File)) {
        & node --check $file.FullName
        if ($LASTEXITCODE -ne 0) { throw "Invalid JavaScript: $($file.Name)" }
    }
    & $phpBinary tests/configuration.php
    if ($LASTEXITCODE -ne 0) { throw 'Configuration checks failed' }
    & $phpBinary tests/structure.php
    if ($LASTEXITCODE -ne 0) { throw 'Structure checks failed' }
    if ($CheckDatabase) {
        & $phpBinary tests/database.php
        if ($LASTEXITCODE -ne 0) { throw 'Database checks failed' }
    }
} finally {
    Pop-Location
}
