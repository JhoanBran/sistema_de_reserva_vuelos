$Owner = "JhoanBran"
$Repo = "sistema_de_reserva_vuelos"
$Branch = "main"
$env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path","User")

# Get token
$result = powershell -Command { $env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path","User"); gh auth token 2>$null }
$Token = $result.Trim()

if (-not $Token) {
    Write-Host "Error: No token"
    exit 1
}

$BaseUrl = "https://api.github.com/repos/$Owner/$Repo/contents"
$Headers = @{
    "Authorization" = "Bearer $Token"
    "Accept" = "application/vnd.github.v3+json"
}

# Get all files
$Files = Get-ChildItem -Recurse -File -Exclude @("*.git", "push-to-github.ps1", "upload.ps1") | ForEach-Object { $_.FullName.Replace("$PWD\", "") }

Write-Host "Uploading $($Files.Count) files..."

foreach ($File in $Files) {
    $FilePath = Join-Path $PWD $File
    if (-not (Test-Path $FilePath)) { continue }
    
    $Content = [Convert]::ToBase64String([System.IO.File]::ReadAllBytes($FilePath))
    $Url = "$BaseUrl/$File"
    
    $Body = @{
        message = "Add $File"
        content = $Content
        branch = $Branch
    } | ConvertTo-Json
    
    try {
        $Response = Invoke-RestMethod -Uri $Url -Method Put -Headers $Headers -Body $Body -ErrorAction SilentlyContinue
        Write-Host "OK: $File"
    }
    catch {
        Write-Host "SKIP: $File"
    }
}

Write-Host "Done!"
