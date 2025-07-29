<!DOCTYPE html>
<html>
<head>
    <title>SK Crackers API Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        button { padding: 10px 15px; margin: 5px; cursor: pointer; }
        .result { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>SK Crackers API Test</h1>
    
    <div class="test-section">
        <h3>Test Products API</h3>
        <button onclick="testGetProducts()">Get All Products</button>
        <button onclick="testCreateProduct()">Create Test Product</button>
        <div id="products-result" class="result"></div>
    </div>

    <div class="test-section">
        <h3>Test Orders API</h3>
        <button onclick="testGetOrders()">Get All Orders</button>
        <button onclick="testCreateOrder()">Create Test Order</button>
        <button onclick="testSearchOrder()">Search Order by Mobile</button>
        <div id="orders-result" class="result"></div>
    </div>

    <script>
        const API_BASE = 'http://localhost/sk-crackers-backend/api';

        async function testGetProducts() {
            try {
                const response = await fetch(`${API_BASE}/products`);
                const data = await response.json();
                document.getElementById('products-result').innerHTML = 
                    '<h4>Products:</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                document.getElementById('products-result').innerHTML = 
                    '<h4>Error:</h4>' + error.message;
            }
        }

        async function testCreateProduct() {
            const productData = {
                name: "Test Cracker",
                image: "/placeholder.svg?height=200&width=280",
                originalPrice: 100,
                currentPrice: 80,
                category: "bombs",
                subcategory: "TEST CATEGORY",
                isSoldOut: false
            };

            try {
                const response = await fetch(`${API_BASE}/products`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(productData)
                });
                const data = await response.json();
                document.getElementById('products-result').innerHTML = 
                    '<h4>Create Product Result:</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                document.getElementById('products-result').innerHTML = 
                    '<h4>Error:</h4>' + error.message;
            }
        }

        async function testGetOrders() {
            try {
                console.log('Fetching orders from:', `${API_BASE}/orders`);
                const response = await fetch(`${API_BASE}/orders`);
                
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                
                const text = await response.text();
                console.log('Raw response:', text);
                
                try {
                    const data = JSON.parse(text);
                    document.getElementById('orders-result').innerHTML = 
                        '<h4>Orders:</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
                } catch (parseError) {
                    document.getElementById('orders-result').innerHTML = 
                        '<h4>JSON Parse Error:</h4><p>' + parseError.message + '</p>' +
                        '<h4>Raw Response:</h4><pre>' + text + '</pre>';
                }
            } catch (error) {
                document.getElementById('orders-result').innerHTML = 
                    '<h4>Fetch Error:</h4>' + error.message;
            }
        }

        async function testCreateOrder() {
            const orderData = {
                name: "Test Customer",
                mobile: "9876543210",
                address: "Test Address, Test City",
                productList: ["Test Cracker x 2", "Another Cracker x 1"],
                totalAmount: 500
            };

            try {
                const response = await fetch(`${API_BASE}/orders`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(orderData)
                });
                const data = await response.json();
                document.getElementById('orders-result').innerHTML = 
                    '<h4>Create Order Result:</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                document.getElementById('orders-result').innerHTML = 
                    '<h4>Error:</h4>' + error.message;
            }
        }

        async function testSearchOrder() {
            try {
                const response = await fetch(`${API_BASE}/orders?mobile=9876543210`);
                const data = await response.json();
                document.getElementById('orders-result').innerHTML = 
                    '<h4>Search Result:</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                document.getElementById('orders-result').innerHTML = 
                    '<h4>Error:</h4>' + error.message;
            }
        }
    </script>
</body>
</html>
