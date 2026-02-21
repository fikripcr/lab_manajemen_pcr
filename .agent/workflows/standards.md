---
description: Project Coding Standards and Architecture Guide
---

# Project Standardization Guide

This document defines the technical standards for the `lab_manajemen_pcr` project. Antigravity MUST follow these rules for every task.

## 1. Module Division & Folder Structure

**Rule:** Keep strict separation between modules in all layers.

- **Controllers**: `app/Http/Controllers/[Module]/[Entity]Controller.php` (e.g., `Sys/RoleController.php`).
- **Services**: `app/Services/[Module]/[Entity]Service.php` (e.g., `Sys/RoleService.php`).
- **Requests**: `app/Http/Requests/[Module]/[Entity]Request.php`.
- **Views**: `resources/views/pages/[module]/[entity]/[action].blade.php` (All lowercase).

**Common Modules:** `sys`, `hr`, `lab`, `pemutu`, `eoffice`, `cbt`, `pmb`, `survei`, `event`.

## 2. Backend Architecture (Service Pattern)

**Rule:** Thick Services, Thin Controllers.

- **Request**: Use dedicated Form Request classes.
- **Controller**: Only handles HTTP concerns. Inject services via constructor: `public function __construct(protected RoleService $roleService) {}`.
- **Service**: Contains all business logic and database transactions (`DB::transaction`).
- **Eager Loading**: ALWAYS use `->with(['relation'])` in Service queries to prevent N+1 issues.
- **Routing**: ALWAYS use Fully Qualified Class Name (FQCN) tuple notation for routes (e.g., `[DashboardController::class, 'index']`), NEVER the legacy string format.

## 3. Model, Database & Security

- **HashidBinding**: Use `App\Traits\HashidBinding` in models.
- **ID Encryption**: Use `encryptId($id)` and `decryptId($hash)` from `SysHelper.php`.
- **Soft Deletes**: Use standard Laravel `SoftDeletes` trait.
- **Activity Logging**: Use `logActivity($name, $description, $subject)` from `SysHelper.php`.
- **Responses**: Use `jsonSuccess($message, $url)` or `jsonError($message)`.

## 4. Tabler Customization

**Rule:** Use `ThemeTabler` logic for UI consistency.

- **Dark Mode**: All custom background colors (body, sidebar, etc.) are DISABLED in Dark Mode to ensure readability.
- **Header Overlap**: The only exception allowed in Dark Mode is the `header-overlap` background in condensed layouts to maintain visual depth.
- **Live Preview**: Handled by `resources/assets/tabler/js/ThemeTabler.js`.

### View Structure
- **No Redundant Wrappers**: Views extending `layouts.tabler.app` MUST NOT wrap their content with `<div class="page-body"><div class="container-xl">` within `@section('content')` because this is already handled by the global layout.

## 5. Mandatory JS Libraries (sys Feature Testing)

Validate JS implementations by comparing with examples in `/sys/test/features`.

### Core Libraries:
- **Flatpickr**: Use `x-tabler.form-input` with `type="date"`, `datetime`, `range`, or `multiple`. 
- **Select2**: Use `x-tabler.form-select` with `type="select2"`. Supports `multiple` and AJAX Search (see `features.blade.php` for AJAX transport logic).
- **FilePond**: Use `.filepond-input` class. Standardized in `admin.js` via `window.initFilePond()`.
- **HugeRTE**: Use `x-tabler.form-textarea` with `type="editor"`.
- **SweetAlert2**: Use global utilities from `CustomSweetAlerts.js`:
  - `showSuccessMessage(title, message)`
  - `showDeleteConfirmation(title, text, confirmBtn)`
  - `handleAjaxResponse(response, successCallback, errorCallback)`

## 6. JavaScript Initialization

- **Unified JS**: Libraries are lazy-loaded in `admin.js`.
- **Re-init Logic**: If adding elements via AJAX/Modals, you MUST manually call:
  - `window.initFlatpickr()`
  - `window.initOfflineSelect2()`
  - `window.initFilePond()`

## 7. Global Rules (CRITICAL)

- **NEVER** modify `admin`, `guest`, or `auth` layouts (unless asked).
- **NEVER** use `git` commands.
- **Logic**: All business logic goes to Services. All UI goes to `x-tabler` components.
