# 🛒 Simple Order System (PHP + Vue.js)

A clean and modular order system with Vue.js frontend and PHP backend (no Laravel). Integrated with PayTabs payment gateway using iframe and AJAX.

## 📦 Structure
```
/frontend - Vue.js frontend
/backend  - PHP backend API
```

## 🔧 Features
- Create order with products and customer info
- View all past orders
- View full order details (payment, refund)
- Checkout using PayTabs iframe
- All calls via AJAX

## 🧪 Setup
### Backend:
```
cd backend
php -S localhost:8000
```

### Frontend:
```
cd frontend
npm install
npm run dev
```

Import `sample_data.sql` into MySQL under database `orders_db`.

## 💳 PayTabs Test
- Profile ID: 132344
- Server Key: SWJ992BZTN-JHGTJBWDLM-BZJKMR2ZHT
- Region: Egypt
