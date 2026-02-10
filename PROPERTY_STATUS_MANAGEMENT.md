# Property Status Management and Wishlist Cleanup

## Overview
This document explains how property status management works in the Realty Hub system and how it automatically handles wishlist cleanup when properties are deactivated.

## How It Works

### 1. Property Status Changes
Properties can have two status values:
- **1**: Active (visible to buyers)
- **0**: Inactive (hidden from buyers)

### 2. Automatic Wishlist Cleanup
When an admin or seller changes a property's status from Active (1) to Inactive (0), the system automatically:
- Removes the property from all buyer wishlists
- Ensures buyers can no longer see the property in their wishlist
- Prevents buyers from adding inactive properties to their wishlist

## Implementation Details

### Admin Panel
- Admins can change property status through the "View Properties" page
- When status changes from 1 to 0, all wishlist entries for that property are automatically removed
- The change is handled by `change_property_status.php`

### Seller Panel
- Sellers can change property status when editing their properties
- Same automatic cleanup occurs when status changes from 1 to 0

### Buyer Experience
- Buyers only see properties with status = 1 in their browsing and wishlist
- If a property is deactivated while in a wishlist, it automatically disappears
- Buyers cannot add inactive properties to their wishlist

## Manual Cleanup
A cleanup script (`cleanup_wishlists.php`) is available for manual execution or scheduling:
- Can be run via command line
- Can be accessed via web with security key
- Removes any orphaned wishlist entries for inactive properties

## Database Structure
- `property` table has a `status` column (1=active, 0=inactive)
- `wishlist` table links buyers to properties
- Foreign key constraints ensure data integrity

## Security
- All status changes are logged through authenticated admin/seller sessions
- Buyer actions are protected by session validation
- Manual cleanup requires authentication or special key