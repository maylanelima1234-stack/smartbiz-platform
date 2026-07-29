param([string]$ProjectPath = (Get-Location).Path)

$ErrorActionPreference = "Stop"
$PackageRoot = Split-Path -Parent $MyInvocation.MyCommand.Path

foreach ($folder in @("app", "config", "tests")) {
    $source = Join-Path $PackageRoot $folder
    if (Test-Path $source) {
        Copy-Item $source $ProjectPath -Recurse -Force
    }
}

$providersFile = Join-Path $ProjectPath "bootstrap\providers.php"
$provider = "    App\Core\Authorization\Providers\AuthorizationServiceProvider::class,"

$content = Get-Content $providersFile -Raw

if ($content -notmatch "AuthorizationServiceProvider") {
    $content = $content -replace `
        "    App\\Core\\Context\\Providers\\PlatformContextServiceProvider::class,", `
        "    App\Core\Context\Providers\PlatformContextServiceProvider::class,`r`n$provider"

    Set-Content $providersFile $content -Encoding UTF8
}

Write-Host "Sprint 004 instalada." -ForegroundColor Green
Write-Host "Execute: php artisan optimize:clear && php artisan test"
