Read-Host "続けるにはENTERキーを押して下さい" 
# ビルトインサーバー起動
cd %~dp0
php -S localhost:8000

# Nuxt起動
npm run dev

