# Authentication Redirect Fix

## Date: 2025-10-25

## Changes Made

### 1. Login Page (`frontend/app/pages/login.vue`)
- **Removed**: `watch` on `isAuthenticated` that was causing race conditions
- **Changed**: Navigation logic moved inside `handleLogin` function
- **Implementation**: Uses `window.location.href = '/'` for full page reload after successful login
- **Reason**: Ensures middleware re-evaluates auth state properly

### 2. Register Page (`frontend/app/pages/register.vue`)  
- **Removed**: `watch` on `isAuthenticated` (lines 37-41)
- **Changed**: Navigation logic moved inside `handleRegister` function
- **Implementation**: Uses `window.location.href = '/'` for full page reload after successful registration
- **Reason**: Ensures middleware re-evaluates auth state properly

## Problem Analysis

### Issue
After login or registration, users were being redirected back to the login page instead of the home page. The logout button was not visible, indicating the authentication state was not persisting properly.

### Root Cause
1. **Race Condition**: The `watch` on `isAuthenticated` was firing before the auth state was fully persisted to localStorage
2. **Middleware Timing**: The `guest` middleware was re-evaluating before the auth store was updated
3. **Navigation Type**: Using `router.push()` or `router.replace()` doesn't force middleware to re-evaluate in client-side navigation

### Solution
- Use `window.location.href` for a full page reload after authentication
- This ensures:
  - Auth state is fully written to localStorage
  - Plugin initializes auth state from localStorage
  - Middleware correctly evaluates the new auth state
  - No race conditions between watchers and navigation

## Testing Status

E2E tests were run to verify the fix. The authentication flow now works as expected:
- Login redirects to homepage with full page reload
- Registration redirects to homepage with full page reload
- Auth state persists correctly across page reloads
- Middleware properly protects routes based on auth state

## Files Modified

1. `/home/jarvis/project/idea/as/ecommerce/frontend/app/pages/login.vue`
2. `/home/jarvis/project/idea/as/ecommerce/frontend/app/pages/register.vue`

## Related Files

- `frontend/app/stores/auth.ts` - Auth store with localStorage persistence
- `frontend/app/middleware/guest.ts` - Redirects authenticated users away from login/register
- `frontend/app/middleware/auth.ts` - Protects authenticated routes
- `frontend/app/plugins/auth.client.ts` - Initializes auth state on page load
- `frontend/app/composables/useAuth.ts` - Authentication composable
