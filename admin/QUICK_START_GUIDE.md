# Admin Module - Quick Start Guide

## 🚀 Getting Started

### Step 1: Database Setup
Make sure your database is set up with these tables:
- `category` - Property categories
- `district` - Location districts
- `property` - Property listings
- `seller` - Seller information
- `users` (with `user_type='buyer'`) - Buyer information
- `feedback` - User feedback

### Step 2: Update Configuration
Edit `config/autoload.php` and set your path:
```php
define("BASE_PATH", "YOUR_PROJECT_PATH_HERE/tonyMCA/");
```

### Step 3: Access Admin Panel
Navigate to: `http://localhost/tonyMCA/admin/login.php`

**Default Login:**
- Username: `admin`
- Password: `12345`

**⚠️ IMPORTANT: Change the password after first login!**

---

## 📋 Admin Features

### Dashboard (`index.php`)
- View statistics at a glance
- Total counts for all entities
- Quick navigation links

### Category Management
- **Add:** `category.php`
- **View/Edit/Delete:** `viewcategory.php`

### District Management
- **Add:** `district.php`
- **View/Edit/Delete:** `viewdistrict.php`

### Property Management
- **View/Delete:** `viewproperty.php`
- Note: Properties are added by sellers

### User Management
- **View Sellers:** `viewseller.php`
- **View Buyers:** `viewbuyer.php`
- **Delete users** if needed

### Feedback Management
- **View/Delete Feedback:** `viewfeedback.php`

---

## 🔐 Security Features

✅ Session-based authentication  
✅ Protected admin pages  
✅ SQL injection prevention  
✅ Input validation  
✅ Secure logout  

---

## 🛠️ Common Tasks

### How to Add a Category
1. Go to Dashboard
2. Click "Add Category" or navigate to `category.php`
3. Enter category name
4. Click Submit

### How to Edit a Category
1. Go to "View Categories" (`viewcategory.php`)
2. Click "Edit" button next to the category
3. Update the name
4. Click "Update Category"

### How to Delete a Record
1. Go to the respective view page
2. Click "Delete" button
3. Confirm deletion in popup
4. Record will be deleted

### How to Logout
- Click "Logout" link in navigation bar
- You'll be redirected to login page

---

## 🔧 Troubleshooting

### Can't Login?
- Check database connection in `dbconn.php`
- Verify username/password in `login.php`
- Clear browser cookies/cache

### Page Not Found?
- Check BASE_PATH in `config/autoload.php`
- Verify file permissions
- Check web server configuration

### Database Errors?
- Verify database credentials in `dbconn.php`
- Check if tables exist
- Ensure database is running

### Session Issues?
- Check if sessions are enabled in PHP
- Verify session_start() is called
- Clear browser cookies

---

## 📁 File Structure

```
admin/
├── index.php              # Dashboard
├── login.php              # Admin Login
├── logout.php             # Logout Handler
├── header.php             # Shared Header (if needed)
│
├── category.php           # Add Category
├── viewcategory.php       # View Categories
├── edit_category.php      # Edit Category
├── del_category.php       # Delete Category
│
├── district.php           # Add District
├── viewdistrict.php       # View Districts
├── edit_district.php      # Edit District
├── del_district.php       # Delete District
│
├── viewproperty.php       # View Properties
├── del_property.php       # Delete Property
│
├── viewseller.php         # View Sellers
├── del_seller.php         # Delete Seller
│
├── viewbuyer.php          # View Buyers
├── del_buyer.php          # Delete Buyer
│
├── viewfeedback.php       # View Feedback
├── del_feedback.php       # Delete Feedback
│
└── dbconn.php             # Database Connection
```

---

## ⚠️ Important Notes

1. **Always use the navigation buttons** to move between pages
2. **Don't use browser back button** after logout
3. **Delete operations are permanent** - use with caution
4. **Keep backup** of your database regularly

---

## 🎯 Best Practices

1. **Regular Backups:** Backup database daily
2. **Monitor Activity:** Check feedback and user registrations regularly
3. **Data Cleanup:** Remove old/invalid data periodically
4. **Update Password:** Change admin password regularly
5. **Test Changes:** Always test in a staging environment first

---

## 📞 Need Help?

If you encounter issues:
1. Check the `BUG_FIXES_SUMMARY.md` for known issues
2. Verify database connectivity
3. Check PHP error logs
4. Ensure all files are properly uploaded

---

**Admin Panel Version:** 1.0  
**Last Updated:** 2025-10-24
