# SK Crackers Backend Setup Guide

## Prerequisites
- XAMPP/WAMP/LAMP server with PHP 7.4+ and MySQL
- phpMyAdmin for database management

## Installation Steps

### 1. Setup Web Server
1. Install XAMPP from https://www.apachefriends.org/
2. Start Apache and MySQL services
3. Place this project folder in `htdocs` directory (usually `C:\xampp\htdocs\sk-crackers-backend`)

### 2. Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Import the database schema:
   - Click "Import" tab
   - Choose `database/schema.sql` file
   - Click "Go" to execute

### 3. Configure Database Connection
1. Edit `config/database.php`
2. Update database credentials:
   \`\`\`php
   private $host = "localhost";
   private $db_name = "sk_crackers";
   private $username = "root";        // Your MySQL username
   private $password = "";            // Your MySQL password
   \`\`\`

### 4. Test the API
1. Open http://localhost/sk-crackers-backend/test_api.php
2. Test all API endpoints to ensure they work correctly

### 5. Update Frontend URLs
Update the API URLs in your HTML files:

#### In checkout.html:
Replace:
\`\`\`javascript
fetch("https://sk-crackers-backend-1.onrender.com/api/orders", {
\`\`\`
With:
\`\`\`javascript
fetch("http://localhost/sk-crackers-backend/api/orders", {
\`\`\`

#### In order-status.html:
Replace:
\`\`\`javascript
return fetch(`https://sk-crackers-backend-1.onrender.com/api/orders/search?mobile=${mobile}`)
\`\`\`
With:
\`\`\`javascript
return fetch(`http://localhost/sk-crackers-backend/api/orders?mobile=${mobile}`)
\`\`\`

#### In admin.html:
Replace:
\`\`\`javascript
fetch("https://sk-crackers-backend-1.onrender.com/api/orders")
\`\`\`
With:
\`\`\`javascript
fetch("http://localhost/sk-crackers-backend/api/orders")
\`\`\`

And:
\`\`\`javascript
fetch(`https://sk-crackers-backend-1.onrender.com/api/orders/${currentOrderId}/status`, {
\`\`\`
With:
\`\`\`javascript
fetch(`http://localhost/sk-crackers-backend/api/orders/${currentOrderId}/status`, {
\`\`\`

## API Endpoints

### Products
- `GET /api/products` - Get all products
- `POST /api/products` - Create new product
- `PUT /api/products` - Update product
- `DELETE /api/products` - Delete product

### Orders
- `GET /api/orders` - Get all orders
- `GET /api/orders?mobile=XXXXXXXXXX` - Search orders by mobile
- `POST /api/orders` - Create new order
- `PUT /api/orders/{id}/status` - Update order status

## Database Tables

### products
- id (Primary Key)
- name
- image
- original_price
- current_price
- category
- subcategory
- is_sold_out
- created_at
- updated_at

### orders
- id (Primary Key)
- customer_name
- mobile_no
- customer_address
- product_list (JSON)
- amount
- status
- date_time
- updated_at

## Troubleshooting

1. **CORS Issues**: Make sure all API files have proper CORS headers
2. **Database Connection**: Verify MySQL is running and credentials are correct
3. **File Permissions**: Ensure web server has read/write access to project files
4. **PHP Extensions**: Make sure PDO and PDO_MySQL extensions are enabled

## Security Notes

For production deployment:
1. Change default database credentials
2. Add input validation and sanitization
3. Implement authentication for admin endpoints
4. Use HTTPS
5. Add rate limiting
6. Validate file uploads properly
