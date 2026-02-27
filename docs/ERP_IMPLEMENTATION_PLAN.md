# ERP Roadmap for Export Company

## 1) Vision & Platform
- **Architecture:** Laravel (backend + RBAC), MySQL/MariaDB, Blade + modern admin theme (or Vue/Inertia in phase 2).
- **Hosting target:** Self-hosted on Hostinger shared/VPS plan with environment-based config.
- **Security baseline:** Role/permission guarded routes, hashed passwords, CSRF protection, audit-ready module actions.

## 2) Module Delivery Plan (Phased)

### Phase A — Foundation (current)
1. User Management
   - User / Role / Permission based access
   - Action-level controls: add, edit, approve, delete
   - Base UI for users, roles, and permission assignment
2. Core layout
   - Modern dashboard shell
   - Informative tiles: orders, pending approvals, inventory, GRN status
3. Hostinger deployment baseline
   - `.env` hardening, caching, queue and scheduler guidance

### Phase B — Masters
4. Item Management
5. Buyer Management
6. Vendor Management
7. Other Master Management

### Phase C — Procurement & Inventory Inbound
8. Buyer Orders
9. Bill of Material
10. Purchase Orders
11. Gate Entry
12. GRN (Goods Received Note)
13. Quality Management

### Phase D — Production Flow
14. Cutting Management
15. Stitching Management
16. Finishing Management
17. Packing Management

### Phase E — Finance
18. Accounts Management

## 3) RBAC Model
- **Roles:** e.g., Super Admin, Admin, Merchandiser, Purchase Manager, QA Manager, Production Manager, Accounts.
- **Permissions:** scoped by module and action:
  - `module.view`
  - `module.add`
  - `module.edit`
  - `module.approve`
  - `module.delete`
- **Assignment:** Users get a role; roles map to many permissions.
- **Enforcement points:** route middleware + policy checks in controllers/services.

## 4) First Module Scope (implemented in this step)
- Create initial DB tables for the user module request:
  1. `tbl_roles` with columns: `id`, `roles`
  2. `tbl_users` with columns: `id`, `user_name`, `email`, `password`, `role`
- `tbl_users.role` links to `tbl_roles.id` through a foreign key.

## 5) Next Immediate Steps
1. Seed initial roles and admin user.
2. Build login + role middleware using the new tables (or map to existing auth tables if preferred).
3. Build Role CRUD and User CRUD with action permissions.
4. Build dashboard tiles and module visibility based on permissions.
5. Prepare Hostinger deployment checklist and production config.


## 6) Implementation Status (Current)
- ✅ Seeded initial roles and admin user via `DatabaseSeeder` + `PermissionSeeder`.
- ✅ Login/authentication mapped to existing Laravel `users` table, with RBAC role/permission payload returned from auth endpoint.
- ✅ Role/User CRUD endpoints protected with action permissions (`user.view/add/edit/delete`, `role.view/add/edit/delete`).
- ✅ Dashboard and sidebar now show module visibility based on assigned permissions.
- ✅ Hostinger deployment checklist added in `docs/HOSTINGER_DEPLOYMENT_CHECKLIST.md`.
