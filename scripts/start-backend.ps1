param([int]$Port = 8000)
$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$portablePhp = Join-Path $projectRoot '.local-tools/php83/php.exe'
$phpBinary = if (Test-Path -LiteralPath $portablePhp) { $portablePhp } else { (Get-Command php -ErrorAction Stop).Source }
$runtimeVersion = & $phpBinary -r 'echo PHP_VERSION;'
if ($LASTEXITCODE -ne 0 -or [version]$runtimeVersion -lt [version]'8.3.0') { throw 'PHP 8.3 or later is required. Update your CLI PHP before starting the backend.' }
Push-Location (Join-Path $projectRoot 'backend')
try { & $phpBinary artisan serve --host=127.0.0.1 --port=$Port } finally { Pop-Location }
