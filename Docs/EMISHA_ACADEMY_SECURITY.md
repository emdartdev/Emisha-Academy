# EMISHA ACADEMY — SECURITY.md

> **Document:** Security Architecture & Security Requirements  
> **Project:** Emisha Academy  
> **Product Type:** Bilingual Bangla-first EdTech Platform  
> **Primary Theme:** Dark  
> **Languages:** Bangla + English  
> **Status:** Production Security Specification  
> **Security Goal:** Defense-in-depth, least privilege, secure-by-default, privacy-first

---

## 1. Security Objective

Emisha Academy must be designed as a **security-first web application** where security is treated as a core product requirement rather than an afterthought.

The platform must protect:

- Student accounts
- Admin accounts
- Worker and Manager accounts
- Course content
- Video and learning materials
- Ebooks and downloadable files
- Payment and order information
- Personal information
- Authentication credentials
- API credentials and secrets
- Internal business data
- Administrative actions
- Audit records
- Uploaded media
- Application infrastructure
- Database records
- Session and authentication tokens

### Core Security Principles

1. Zero Trust
2. Least Privilege
3. Secure by Default
4. Defense in Depth
5. Fail Closed
6. Separation of Duties
7. Explicit Authorization
8. Input Validation
9. Output Encoding
10. Secrets Never in Source Code
11. Complete Auditability
12. Privacy by Design
13. Secure Development Lifecycle
14. Continuous Monitoring
15. Rapid Incident Response

---

# 2. Security Threat Model

The platform must assume that attackers may attempt to:

- Guess or brute-force passwords
- Steal sessions
- Abuse APIs
- Bypass authorization
- Access another student's data
- Escalate student privileges to worker/admin
- Manipulate course prices
- Manipulate payment status
- Upload malicious files
- Inject SQL
- Inject JavaScript
- Perform CSRF attacks
- Perform SSRF attacks
- Abuse file downloads
- Scrape protected course content
- Enumerate users
- Abuse password reset
- Exploit vulnerable dependencies
- Attack admin dashboards
- Steal API keys
- Exploit misconfigured storage
- Modify database records
- Replay payment/webhook requests
- Flood APIs
- Abuse search/filter endpoints
- Exploit insecure direct object references
- Exploit misconfigured CORS
- Use compromised accounts
- Abuse legitimate application functionality
- Exploit infrastructure or deployment mistakes

---

# 3. Security Architecture

Recommended security layers:

```text
                         INTERNET
                            │
                            ▼
                  ┌────────────────────┐
                  │ DNS / CDN / WAF     │
                  │ DDoS Protection     │
                  └─────────┬──────────┘
                            │
                            ▼
                  ┌────────────────────┐
                  │ Reverse Proxy      │
                  │ TLS / Headers      │
                  └─────────┬──────────┘
                            │
             ┌──────────────┴──────────────┐
             ▼                             ▼
     ┌────────────────┐            ┌────────────────┐
     │ Public Frontend│            │ API / Backend  │
     └────────────────┘            └───────┬────────┘
                                           │
                              ┌────────────┼────────────┐
                              ▼            ▼            ▼
                         Auth Layer    RBAC/ABAC     Validation
                              │            │            │
                              └────────────┼────────────┘
                                           ▼
                                  ┌────────────────┐
                                  │ Service Layer  │
                                  └───────┬────────┘
                                          │
                         ┌────────────────┼────────────────┐
                         ▼                ▼                ▼
                    PostgreSQL       Object Storage     Queue/Jobs
                         │                │                │
                         └────────────────┼────────────────┘
                                          ▼
                                  Audit / Monitoring
                                          │
                                          ▼
                                Security Alerting
```

No public request should directly access the database.

---

# 4. Security Zones

The application should be divided logically into:

## 4.1 Public Zone

Examples:

- Homepage
- Public course pages
- Blog
- Public ebook information
- Public webinars
- About
- Contact
- FAQ

Public endpoints must not expose private database fields.

## 4.2 Student Zone

Examples:

- Dashboard
- My Courses
- Learning content
- Orders
- Ebooks
- Profile
- Notifications

Student A must never be able to access Student B's private records.

## 4.3 Worker Zone

Examples:

- Assigned tasks
- Content moderation
- Course content management
- Student support tasks

Workers must only access resources explicitly assigned to them.

## 4.4 Manager Zone

Examples:

- Staff management
- Course management
- Batch management
- Analytics
- Content review

Managers must not automatically receive unrestricted system-administrator privileges.

## 4.5 Admin Zone

Examples:

- Users
- Roles
- Permissions
- System settings
- Audit logs
- Payments
- Media library
- Security configuration

Administrative endpoints require elevated authentication and authorization.

---

# 5. Identity & Authentication Security

## 5.1 Password Requirements

Passwords must:

- Never be stored in plaintext
- Never be logged
- Never be sent through email
- Be hashed using a modern password hashing algorithm such as Argon2id
- Have configurable minimum length
- Reject commonly breached passwords
- Support password change
- Support secure password reset

Avoid reversible encryption for passwords.

## 5.2 Password Hashing

Recommended:

```text
Argon2id
```

Fallback:

```text
bcrypt with an appropriately strong cost factor
```

Never use:

```text
MD5
SHA-1
Plain SHA-256
Custom password hashing
```

## 5.3 Login Protection

Implement:

- Rate limiting
- Progressive delay
- Login attempt monitoring
- Generic authentication error messages
- Suspicious login detection
- Optional CAPTCHA/challenge after repeated failures
- Account security notifications

Avoid revealing whether an email exists during login/reset flows.

Bad:

```text
This email does not exist.
```

Better:

```text
If an account exists for this email, instructions will be sent.
```

---

# 6. Multi-Factor Authentication

MFA must be supported for:

- Super Admin
- Admin
- Manager
- Worker
- Payment-related privileged accounts

Preferred methods:

1. Passkeys/WebAuthn
2. Authenticator application TOTP
3. Backup recovery codes

SMS should not be the primary high-security MFA mechanism when stronger methods are available.

### Mandatory MFA

Administrative roles should require MFA.

---

# 7. Session Security

Sessions must use secure server-managed mechanisms where appropriate.

Cookies must use:

```text
Secure
HttpOnly
SameSite=Lax or Strict
```

Session requirements:

- Short lifetime for privileged sessions
- Session rotation after login
- Session rotation after privilege changes
- Logout invalidation
- Password-change session invalidation
- MFA-change session invalidation
- Device/session management
- Ability to revoke individual sessions
- Global logout for security incidents

Never store sensitive authentication tokens in:

```text
localStorage
sessionStorage
URL query parameters
```

unless there is a carefully reviewed architectural reason.

---

# 8. Authorization

Authentication answers:

> Who are you?

Authorization answers:

> What are you allowed to do?

Every protected API request must perform authorization independently.

Never rely only on frontend route guards.

---

# 9. RBAC — Role-Based Access Control

Recommended roles:

```text
STUDENT
WORKER
MANAGER
ADMIN
SUPER_ADMIN
```

Example:

| Action | Student | Worker | Manager | Admin | Super Admin |
|---|---:|---:|---:|---:|---:|
| View public courses | ✓ | ✓ | ✓ | ✓ | ✓ |
| Enroll | ✓ | ✓ | ✓ | ✓ | ✓ |
| View own dashboard | ✓ | ✓ | ✓ | ✓ | ✓ |
| Manage assigned content | — | ✓ | ✓ | ✓ | ✓ |
| Manage courses | — | Assigned | ✓ | ✓ | ✓ |
| Manage workers | — | — | ✓ | ✓ | ✓ |
| Manage users | — | — | Limited | ✓ | ✓ |
| Manage roles | — | — | — | Limited | ✓ |
| System settings | — | — | — | ✓ | ✓ |
| Security settings | — | — | — | Limited | ✓ |
| Audit logs | Own | Limited | Relevant | ✓ | ✓ |

Permissions should be granular rather than relying only on broad roles.

---

# 10. Permission Model

Use permissions such as:

```text
course.view
course.create
course.update
course.delete
course.publish

lesson.view
lesson.create
lesson.update
lesson.delete

student.view
student.update
student.export

order.view
order.refund
order.update

ebook.view
ebook.create
ebook.update
ebook.delete

user.view
user.create
user.update
user.disable

role.view
role.create
role.update
role.assign

settings.view
settings.update

audit.view
```

Sensitive permissions must require explicit authorization.

---

# 11. Object-Level Authorization

Prevent IDOR/BOLA vulnerabilities.

Unsafe:

```http
GET /api/orders/1001
```

Simply because the user knows `1001`.

The backend must verify:

```text
Does the authenticated user own this order?
OR
Does the user have explicit permission to access it?
```

Authorization must happen server-side.

---

# 12. Database Security

Use:

- Parameterized queries
- ORM/query builders safely
- Prepared statements
- Database roles
- Least privilege
- Encryption in transit
- Encryption at rest
- Connection pooling
- Restricted network access
- Automated backups
- Point-in-time recovery where supported

Never construct SQL using raw user input.

Bad:

```sql
SELECT * FROM users WHERE id = '$id';
```

Use parameterized queries instead.

---

# 13. Row-Level Security

Where supported, implement database-level Row-Level Security.

Example concept:

```text
Student:
    Can read only own private records.

Worker:
    Can read assigned records.

Manager:
    Can read records within authorized scope.

Admin:
    Can read administrative datasets.

Super Admin:
    Full system access.
```

Application-level authorization should remain in place even when database-level policies exist.

---

# 14. Sensitive Data Protection

Classify data:

### Public

- Course title
- Public description
- Blog posts
- Public instructor profile

### Internal

- Staff notes
- Internal task information
- Operational analytics

### Confidential

- Student profile
- Orders
- Payment metadata
- Private learning activity

### Highly Sensitive

- Password hashes
- Authentication secrets
- API keys
- Recovery codes
- Payment-provider secrets
- Encryption keys

Highly sensitive data must have strict access controls.

---

# 15. Encryption

## 15.1 Data in Transit

Use HTTPS everywhere.

Minimum:

```text
TLS 1.2+
```

Prefer modern TLS configuration.

Redirect HTTP to HTTPS.

Enable HSTS after validating the deployment.

## 15.2 Data at Rest

Encrypt:

- Database storage
- Backups
- Object storage
- Sensitive configuration
- Server disks where applicable

## 15.3 Application-Level Encryption

For extremely sensitive fields, consider application-level encryption.

Encryption keys must never be stored beside encrypted data without appropriate key management.

---

# 16. Secrets Management

Never commit:

```text
.env
API keys
database passwords
JWT secrets
private keys
payment secrets
SMTP passwords
cloud credentials
```

to Git.

Use:

- Environment variables
- Managed secret stores
- Deployment platform secret management
- Secret rotation

Example:

```text
DATABASE_URL
AUTH_SECRET
PAYMENT_SECRET_KEY
PAYMENT_WEBHOOK_SECRET
STORAGE_ACCESS_KEY
STORAGE_SECRET_KEY
EMAIL_API_KEY
```

Secrets must be different across:

```text
development
staging
production
```

---

# 17. API Security

Every API endpoint must define:

```text
Authentication
Authorization
Validation
Rate Limit
Input Schema
Output Schema
Audit Requirement
```

Example:

```text
POST /api/courses

Auth:
    Required

Permission:
    course.create

Validation:
    Required

Rate Limit:
    Enabled

Audit:
    Required
```

---

# 18. API Rate Limiting

Rate limit sensitive endpoints:

- Login
- Register
- Password reset
- OTP
- Search
- Contact form
- Checkout
- Payment initiation
- File upload
- Export
- Admin APIs

Use different limits by endpoint sensitivity.

---

# 19. Anti-Automation Protection

Protect against:

- Credential stuffing
- Account enumeration
- Brute-force attacks
- Automated registration
- Fake checkout requests
- Spam contact submissions
- API scraping

Use:

- Rate limits
- Bot detection
- Challenge mechanisms
- IP reputation where appropriate
- Account/device anomaly detection

Do not rely on IP blocking alone.

---

# 20. Input Validation

Validate every user-controlled value:

- Text
- IDs
- URLs
- Numbers
- Dates
- Files
- Query parameters
- Form submissions
- JSON bodies
- Headers where applicable

Use strict schemas.

Example:

```text
courseId → UUID/integer according to schema
price → positive decimal
email → validated email format
language → enum
status → predefined enum
```

Reject unexpected fields where practical.

---

# 21. XSS Protection

Prevent:

- Stored XSS
- Reflected XSS
- DOM XSS

Rules:

- Escape output
- Sanitize rich text
- Avoid unsafe HTML rendering
- Use trusted sanitization libraries
- Apply Content Security Policy
- Never inject raw user HTML without sanitization

Rich-text editors must sanitize HTML server-side.

---

# 22. Content Security Policy

Implement a restrictive CSP.

Conceptual policy:

```text
default-src 'self';
script-src 'self';
style-src 'self' 'unsafe-inline';
img-src 'self' data: https:;
font-src 'self' https:;
connect-src 'self' https:;
frame-ancestors 'none';
base-uri 'self';
form-action 'self';
```

The exact policy must be adapted to actual third-party services.

Avoid:

```text
script-src *
```

and unnecessary:

```text
unsafe-eval
```

---

# 23. CSRF Protection

For cookie-authenticated state-changing requests, implement CSRF protection.

Protect:

```text
POST
PUT
PATCH
DELETE
```

Use:

- SameSite cookies
- CSRF tokens where required
- Origin/Referer validation where appropriate

GET requests must not perform destructive state changes.

---

# 24. CORS

CORS must use an explicit allowlist.

Avoid:

```text
Access-Control-Allow-Origin: *
```

for authenticated APIs.

Allow only trusted origins.

Example:

```text
https://emishaacademy.com
https://www.emishaacademy.com
```

Development origins should not be enabled in production.

---

# 25. Security Headers

Recommended headers include:

```text
Strict-Transport-Security
Content-Security-Policy
X-Content-Type-Options: nosniff
Referrer-Policy
Permissions-Policy
X-Frame-Options
```

Prefer CSP `frame-ancestors` for modern clickjacking protection.

---

# 26. Clickjacking Protection

Prevent sensitive pages from being embedded in unauthorized frames.

Protect:

- Login
- Checkout
- Admin
- Dashboard
- Payment
- Profile
- Settings

Use CSP:

```text
frame-ancestors 'none';
```

or an explicit trusted-origin policy where embedding is actually required.

---

# 27. File Upload Security

File uploads are a major attack surface.

Never trust:

```text
filename
extension
MIME type
client-provided metadata
```

Validate:

- Extension
- MIME type
- File signature/magic bytes
- File size
- Image dimensions
- Archive structure where applicable

Store uploads outside the executable application directory.

Rename files using generated identifiers.

Example:

```text
original.pdf
```

becomes:

```text
01JXYZ...pdf
```

---

# 28. Malware Scanning

Where appropriate, uploaded files should pass through malware scanning before becoming available to users.

Recommended flow:

```text
Upload
  ↓
Quarantine
  ↓
Validate
  ↓
Malware Scan
  ↓
Approve
  ↓
Private Storage
  ↓
Controlled Delivery
```

Unscanned files should not become publicly accessible.

---

# 29. Secure Video Protection

Premium course videos should not be exposed as permanent public URLs.

Prefer:

- Private object storage
- Signed URLs
- Short expiration
- Access checks
- Enrollment checks
- Optional DRM where business requirements justify it

Example:

```text
Student requests lesson
        ↓
Check authentication
        ↓
Check enrollment
        ↓
Check course access
        ↓
Generate short-lived signed URL
        ↓
Deliver video
```

Never place permanent storage credentials in frontend code.

---

# 30. Ebook Protection

Paid ebooks should use:

- Private storage
- Enrollment/purchase verification
- Short-lived signed download URLs
- Download logging
- Optional download limits
- Revocation capability

Never expose private storage buckets publicly.

---

# 31. Image & Media Security

Media should be served from controlled storage/CDN.

For user uploads:

- Validate file type
- Strip dangerous metadata where appropriate
- Generate safe filenames
- Enforce size limits
- Resize images server-side
- Reject malformed files
- Prevent executable uploads

---

# 32. Payment Security

Emisha Academy should minimize direct handling of card data.

Prefer trusted payment providers and hosted/tokenized payment flows.

The application should not store:

- Full card numbers
- CVV
- Magnetic stripe data
- Sensitive authentication data

Store only necessary transaction metadata.

Example:

```text
order_id
provider
transaction_id
amount
currency
status
created_at
verified_at
```

---

# 33. Payment Amount Integrity

Never trust the amount sent by the browser.

Unsafe:

```json
{
  "courseId": 12,
  "amount": 100
}
```

The backend must calculate the authoritative amount:

```text
courseId
    ↓
Load course price
    ↓
Apply validated discount
    ↓
Calculate final amount
    ↓
Create payment
```

---

# 34. Payment Webhook Security

Webhook endpoints must verify:

- Signature
- Timestamp
- Event ID
- Provider
- Transaction ID
- Expected amount
- Expected currency
- Expected order
- Idempotency

Never mark an order as paid solely because the browser says:

```text
payment=success
```

Only verified provider events may finalize payment.

---

# 35. Idempotency

Payment and order operations must be idempotent.

Example:

```text
Webhook received
      ↓
Event ID already processed?
      ├── Yes → Ignore safely
      └── No  → Validate → Process → Record
```

This prevents duplicate enrollment/order creation.

---

# 36. Admin Dashboard Security

Admin dashboard must have:

- MFA
- Strong session controls
- IP/device anomaly detection where appropriate
- Permission checks
- Audit logging
- Shorter session timeout
- Re-authentication for critical actions
- Confirmation for destructive operations

Critical actions may require:

```text
Password re-entry
+
MFA
```

Examples:

- Delete user
- Change role
- Disable admin
- Change payment settings
- Change security settings
- Export sensitive data

---

# 37. Privileged Action Protection

High-risk operations require additional confirmation.

Examples:

```text
Delete user
Delete course
Refund payment
Change role
Publish course
Unpublish course
Change admin permissions
Change system configuration
```

Use:

```text
Confirmation dialog
+
Permission check
+
Audit log
```

For very sensitive operations:

```text
Re-authentication
+
MFA
+
Audit log
```

---

# 38. Audit Logging

Create tamper-resistant audit records for:

- Login
- Logout
- Failed login
- Password change
- MFA change
- Role change
- Permission change
- User creation
- User suspension
- Course creation
- Course publication
- Course deletion
- Price changes
- Refunds
- Payment events
- File uploads
- File deletion
- Data exports
- Security setting changes

Audit event structure:

```json
{
  "actor_id": "...",
  "action": "course.publish",
  "resource_type": "course",
  "resource_id": "...",
  "ip_hash_or_address": "...",
  "user_agent": "...",
  "timestamp": "...",
  "result": "success"
}
```

Do not log passwords, tokens, payment secrets, or sensitive authentication data.

---

# 39. Log Security

Logs must:

- Avoid secrets
- Avoid passwords
- Avoid access tokens
- Avoid unnecessary personal data
- Use structured format
- Have retention rules
- Have restricted access
- Be monitored for suspicious activity

Protect logs against unauthorized modification.

---

# 40. Security Monitoring

Monitor:

- Repeated failed logins
- Sudden admin activity
- Permission changes
- Large data exports
- Unusual download volume
- Repeated 403 responses
- Repeated 404 probing
- API rate-limit violations
- Suspicious payment events
- Abnormal file uploads
- Multiple geographic login anomalies where appropriate

---

# 41. Alerting

Security alerts should be generated for:

### Critical

- Super-admin compromise indicators
- Massive data export
- Repeated privileged authorization failures
- Secret leakage
- Database compromise indicators
- Payment manipulation indicators

### High

- Repeated admin login failures
- Unexpected role escalation
- Suspicious file uploads
- Webhook signature failures at scale

### Medium

- Repeated API abuse
- Suspicious login patterns
- Excessive failed requests

---

# 42. Account Security Notifications

Notify users about significant events:

- New login
- Password changed
- Email changed
- MFA enabled/disabled
- Password reset
- New device/session
- Account disabled
- Suspicious activity

Security notifications should not expose sensitive secrets.

---

# 43. Email Security

Transactional emails should:

- Use authenticated sending
- Use SPF
- Use DKIM
- Use DMARC
- Avoid sensitive information
- Use secure links
- Use short-lived tokens for sensitive actions

Password reset links must:

- Expire
- Be single-use
- Be invalidated after password reset

---

# 44. Password Reset Security

Reset process:

```text
Request reset
      ↓
Generic response
      ↓
Generate cryptographically secure token
      ↓
Store hashed token
      ↓
Short expiration
      ↓
Email secure link
      ↓
Verify token
      ↓
Allow password reset
      ↓
Invalidate token
      ↓
Invalidate existing sessions
```

Never store reset tokens in plaintext if avoidable.

---

# 45. Account Enumeration Protection

Avoid revealing whether a user exists through:

- Login
- Registration
- Password reset
- Email verification

Use consistent responses and carefully controlled timing where practical.

---

# 46. Data Privacy

Collect only data necessary for platform operation.

Provide:

- Privacy policy
- Data retention rules
- User data access process
- Account deletion process where legally/business appropriate
- Data correction capability
- Consent mechanisms where required
- Cookie controls where required

Avoid collecting unnecessary personal information.

---

# 47. Data Export Security

Sensitive exports require:

- Explicit permission
- Authentication
- Authorization
- Optional MFA/re-authentication
- Audit logging
- Expiring download links
- Encryption
- Access expiration

Exports must not be publicly accessible.

---

# 48. Data Deletion

Deletion workflows should distinguish:

```text
Soft delete
Hard delete
Anonymization
Archive
```

Financial/audit records may require retention according to applicable legal/business requirements.

Deletion must be authorized and logged.

---

# 49. Database Backup Security

Backups must be:

- Automated
- Encrypted
- Access-controlled
- Tested
- Versioned
- Monitored

Recommended strategy:

```text
Daily backup
+
Point-in-time recovery where supported
+
Off-site/isolated backup
+
Periodic restore test
```

A backup that has never been restored is not considered fully verified.

---

# 50. Disaster Recovery

Define:

### RPO

Maximum acceptable data loss.

### RTO

Maximum acceptable recovery time.

Example targets should be decided by business requirements rather than hardcoded assumptions.

Document:

- Backup location
- Restore process
- Database recovery
- Storage recovery
- DNS recovery
- Secret recovery
- Deployment recovery
- Emergency contacts

---

# 51. Dependency Security

Continuously scan:

- npm packages
- Composer packages
- Python packages if used
- Docker images
- OS packages

Use:

```text
Dependabot/Renovate
SCA
Lockfiles
Security advisories
```

Do not blindly update dependencies without testing.

Remove unused dependencies.

---

# 52. Supply Chain Security

Protect the build pipeline:

- Lock dependency versions
- Review dependency changes
- Use trusted package registries
- Scan packages
- Protect CI secrets
- Restrict CI permissions
- Sign releases where appropriate
- Prevent unauthorized workflow modifications

---

# 53. Secure Coding Standards

Developers must follow:

- OWASP secure coding principles
- Input validation
- Output encoding
- Least privilege
- Error handling
- Secure authentication
- Secure authorization
- Safe database access
- Secret management

Code review is required for security-sensitive changes.

---

# 54. Error Handling

Production errors must not reveal:

- Stack traces
- SQL queries
- File paths
- Secret values
- Internal service names
- Environment variables
- Database details

User-facing:

```text
Something went wrong. Please try again.
```

Detailed errors belong in controlled server-side logs.

---

# 55. Debug Mode

Production must never run with development debugging enabled.

Example:

```text
DEBUG=false
```

Development and production configurations must be separate.

---

# 56. Environment Isolation

Maintain separate:

```text
Development
Staging
Production
```

Do not connect local development directly to production databases.

Do not use production secrets in development.

---

# 57. Production Deployment Security

Production deployment must use:

- HTTPS
- Secure environment variables
- Protected CI/CD
- Minimal server privileges
- Firewall rules
- Monitoring
- Automated security scanning
- Database migration controls
- Rollback capability

---

# 58. Server Security

Servers must use:

- Minimal installed software
- Regular security updates
- Firewall
- SSH key authentication where applicable
- Disabled unnecessary services
- Restricted admin ports
- Fail2ban or equivalent where appropriate
- Monitoring
- Centralized logs

Root access should be minimized.

---

# 59. Container Security

If Docker is used:

- Use minimal base images
- Scan images
- Do not run as root unnecessarily
- Use read-only filesystem where practical
- Drop unnecessary Linux capabilities
- Avoid privileged containers
- Keep secrets outside images
- Pin base image versions

Never put secrets in:

```dockerfile
ENV
COPY .env
```

---

# 60. SSRF Protection

If the backend fetches remote URLs:

- Validate destination URLs
- Restrict protocols
- Block localhost
- Block private IP ranges
- Block cloud metadata endpoints
- Follow redirects safely
- Apply network egress controls
- Use allowlists for trusted external services

Never blindly fetch arbitrary user-provided URLs.

---

# 61. Open Redirect Protection

Do not redirect users to arbitrary URLs supplied by query parameters.

Unsafe:

```text
/redirect?url=https://attacker.example
```

Use allowlisted destinations.

---

# 62. Path Traversal Protection

Never directly concatenate user input into filesystem paths.

Block:

```text
../
..\ 
encoded traversal
absolute paths
```

Use generated file identifiers and controlled storage APIs.

---

# 63. Prototype Pollution / Unsafe Object Handling

For JavaScript/Node.js applications:

- Validate object schemas
- Avoid unsafe object merging
- Avoid trusting arbitrary JSON keys
- Keep dependencies updated
- Avoid dynamic evaluation

Never use:

```text
eval()
new Function()
```

with user-controlled data.

---

# 64. SQL Injection Protection

Mandatory:

```text
Prepared statements
Parameterized queries
ORM safe APIs
Input validation
Least-privilege database users
```

Never concatenate user input into SQL.

---

# 65. NoSQL Injection Protection

If NoSQL is used:

- Validate operators
- Validate object types
- Reject unexpected query structures
- Use strict schemas
- Never directly pass user JSON into database query objects

---

# 66. Command Injection Protection

Never execute shell commands with raw user input.

Avoid:

```text
exec(userInput)
```

If command execution is unavoidable:

- Use fixed commands
- Use allowlists
- Pass arguments separately
- Run under least privilege
- Sandbox where possible

---

# 67. Admin URL Security

Admin routes should not rely on obscurity.

Do not consider:

```text
/admin-secret-panel
```

a security mechanism.

Security must come from:

```text
Authentication
+
MFA
+
Authorization
+
Monitoring
```

---

# 68. Frontend Security

Frontend must:

- Never contain private secrets
- Never contain database credentials
- Never contain payment secret keys
- Avoid unsafe HTML
- Validate UX input
- Handle auth state securely
- Avoid exposing internal API responses
- Avoid trusting client-side roles

Frontend permission checks are UX protections only.

Backend authorization is authoritative.

---

# 69. Source Map Security

Production source maps should be reviewed carefully.

Do not expose:

- Internal source code
- Secrets
- Private paths
- Debug information

If public source maps are not required, restrict them.

---

# 70. API Response Minimization

Do not return:

```json
{
  "id": 1,
  "email": "...",
  "password_hash": "...",
  "reset_token": "...",
  "internal_notes": "..."
}
```

Return only fields required by the client.

Use explicit response schemas.

---

# 71. Pagination Abuse Protection

Protect list endpoints against:

```text
?page=999999999
?limit=999999999
```

Apply:

```text
Maximum page size
Maximum offset
Cursor pagination where appropriate
Query timeout
```

---

# 72. Search Security

Search endpoints must have:

- Rate limits
- Query length limits
- Validation
- Pagination
- Abuse detection

Search should not expose hidden/private records.

---

# 73. GraphQL Security — If Used

If GraphQL is used:

- Depth limiting
- Complexity limiting
- Query timeout
- Introspection restrictions where appropriate
- Authorization per resolver
- Rate limiting
- Input validation

---

# 74. WebSocket Security — If Used

Use:

- Authentication
- Authorization
- Origin validation
- Message schema validation
- Rate limits
- Connection limits
- Session expiration

Never assume an authenticated socket has unlimited permissions.

---

# 75. Notification Security

Notifications must respect user authorization.

A notification should never expose another user's:

- Order
- Email
- Course progress
- Personal information
- Private message

---

# 76. Internationalization Security

Bangla and English translations must be treated as application content.

Translation keys must not allow arbitrary HTML injection.

Example:

```text
{{user_name}}
```

must be escaped according to output context.

---

# 77. Rich Text Editor Security

For course descriptions, blog content, lessons, and announcements:

- Sanitize HTML
- Remove scripts
- Remove dangerous URLs
- Restrict iframe sources
- Restrict embedded content
- Validate links
- Sanitize on server side

Never trust frontend sanitization alone.

---

# 78. Content Publishing Security

Publishing workflow:

```text
Draft
  ↓
Review
  ↓
Approved
  ↓
Published
```

For important content, separate:

```text
Author
Reviewer
Publisher
```

This creates separation of duties.

---

# 79. Course Price Security

Price changes require:

- Permission
- Validation
- Audit log
- Previous price tracking
- New price tracking
- Actor tracking

Example:

```text
Old Price: 5000
New Price: 4500
Changed By: Admin ID
Timestamp: ...
Reason: ...
```

---

# 80. Coupon Security

Coupons must validate:

- Expiration
- Usage limit
- Per-user limit
- Course applicability
- Minimum purchase
- Discount type
- Maximum discount
- Active status

Never calculate final discount only on the frontend.

---

# 81. Enrollment Security

Enrollment must verify:

```text
User authenticated
+
Payment verified
OR
Admin-approved enrollment
+
Course exists
+
Enrollment not already active
```

Do not trust:

```text
POST /enroll
{
  "userId": 123
}
```

The authenticated user identity should come from the secure session/token.

---

# 82. Learning Progress Security

Students should only modify their own progress unless authorized staff functionality explicitly allows otherwise.

Protect:

- Completion status
- Watch progress
- Quiz attempts
- Certificates
- Attendance
- Course access

---

# 83. Certificate Security

Certificates should have:

- Unique certificate ID
- Verification endpoint
- Integrity protection
- Issuance audit
- Revocation status

Example:

```text
/verify/certificate/EMI-2026-XXXX
```

Do not expose unnecessary student information through verification.

---

# 84. Quiz & Exam Security

Protect against:

- Answer endpoint manipulation
- Repeated submissions
- Unauthorized access
- Attempt tampering
- Score manipulation
- Time manipulation

Server must calculate authoritative results.

---

# 85. Worker Dashboard Security

Workers must only see:

```text
Assigned tasks
Assigned students
Assigned courses
Authorized operational data
```

Do not give workers full student database access by default.

---

# 86. Manager Dashboard Security

Managers should have scoped permissions.

Examples:

```text
Course manager
Student manager
Content manager
Operations manager
```

Avoid giving every manager every permission.

---

# 87. Super Admin Security

Super Admin accounts should be extremely limited.

Requirements:

- MFA
- Strong authentication
- Short sessions
- Security alerts
- Audit logging
- Re-authentication for critical changes
- Emergency account recovery
- Minimal number of super-admin accounts

---

# 88. Break-Glass Access

Maintain an emergency recovery procedure.

Break-glass access must:

- Be tightly restricted
- Require strong authentication
- Be logged
- Trigger alerts
- Be reviewed afterward

---

# 89. Security Testing

Before production:

### Static Analysis

```text
SAST
```

### Dependency Scanning

```text
SCA
```

### Dynamic Testing

```text
DAST
```

### API Testing

Test:

- Authentication bypass
- Authorization bypass
- IDOR/BOLA
- Injection
- Rate limiting
- CSRF
- CORS
- File uploads

---

# 90. Penetration Testing

Perform periodic penetration testing covering:

- Public website
- APIs
- Authentication
- Student dashboard
- Worker dashboard
- Manager dashboard
- Admin dashboard
- File uploads
- Payment workflows

Critical findings must be remediated before release where practical.

---

# 91. Security Test Matrix

| Area | Test |
|---|---|
| Authentication | Brute force |
| Authentication | Session fixation |
| Authorization | IDOR/BOLA |
| Authorization | Privilege escalation |
| API | Rate limit bypass |
| API | Input injection |
| Frontend | XSS |
| Forms | CSRF |
| Uploads | Malware |
| Uploads | MIME spoofing |
| Storage | Public bucket exposure |
| Payment | Amount manipulation |
| Payment | Webhook forgery |
| Admin | Privilege escalation |
| Database | SQL injection |
| Infrastructure | TLS configuration |
| Dependencies | Known CVEs |

---

# 92. CI/CD Security Gates

Production deployment should fail when critical security checks fail.

Example:

```text
Code Push
   ↓
Lint
   ↓
Unit Tests
   ↓
Integration Tests
   ↓
SAST
   ↓
Dependency Scan
   ↓
Build
   ↓
Container Scan
   ↓
DAST/Staging Tests
   ↓
Security Approval
   ↓
Production
```

---

# 93. Git Security

Repository must:

- Require protected branches
- Require pull requests
- Require code review
- Prevent direct production pushes
- Scan secrets
- Scan dependencies
- Protect CI/CD workflows

Use secret scanning tools.

---

# 94. Environment Security Matrix

| Secret/Data | Development | Staging | Production |
|---|---|---|---|
| DB credentials | Separate | Separate | Separate |
| Payment keys | Sandbox | Sandbox/Test | Production |
| Auth secret | Unique | Unique | Unique |
| Storage credentials | Separate | Separate | Separate |
| Email credentials | Test | Test | Production |
| Analytics | Test | Test | Production |

Never reuse production credentials across environments.

---

# 95. Third-Party Integration Security

Every integration must have:

- Minimum required permissions
- API key rotation
- Webhook verification
- Timeout
- Retry limits
- Error handling
- Monitoring
- Vendor review

Examples:

```text
Payment provider
Email provider
Cloud storage
Analytics
Video platform
SMS provider
OAuth provider
```

---

# 96. Webhook Security

Every webhook should use:

```text
Signature verification
Timestamp validation
Replay protection
Idempotency
Event validation
```

Never trust webhook payloads without verification.

---

# 97. Rate Limit Architecture

Recommended categories:

```text
Global API
Authentication
Password Reset
Search
Uploads
Checkout
Payment
Admin
Exports
```

Use distributed rate limiting if multiple application instances exist.

---

# 98. DDoS Protection

Use infrastructure-level protection where available:

```text
CDN
WAF
Rate limiting
Caching
Connection limits
Bot mitigation
```

Static assets should be served through CDN where practical.

---

# 99. WAF

Configure WAF protections for common threats:

- SQL injection
- XSS
- Path traversal
- Malicious bots
- Request flooding
- Known exploit signatures

WAF is an additional layer, not a replacement for secure application code.

---

# 100. Security Headers Checklist

Production response should be reviewed for:

```text
HTTPS
HSTS
CSP
X-Content-Type-Options
Referrer-Policy
Permissions-Policy
Frame protection
Secure cookies
```

---

# 101. Cookie Security Checklist

Authentication cookies should use:

```text
Secure = true
HttpOnly = true
SameSite = Lax/Strict
```

Cookie scope must be as narrow as practical.

Avoid overly broad domain cookies.

---

# 102. Session Timeout

Suggested model:

```text
Student:
    Normal session

Worker:
    Shorter inactivity timeout

Manager:
    Shorter inactivity timeout

Admin:
    Short privileged session

Super Admin:
    Short privileged session + MFA
```

Exact durations should be configurable according to risk and operational needs.

---

# 103. Device & Session Management

Users should be able to see:

```text
Device
Browser
Approximate location
Last active
Session status
```

Allow:

```text
Logout this device
Logout all devices
```

Sensitive accounts should receive alerts for new sessions.

---

# 104. Security UX

Security controls should be understandable in both:

```text
বাংলা
English
```

Examples:

```text
নতুন ডিভাইস থেকে লগইন হয়েছে
New device sign-in detected
```

Do not sacrifice security because of language switching.

---

# 105. Accessibility & Security

Security controls must remain accessible.

Ensure:

- Keyboard support
- Screen-reader labels
- Clear error messages
- Visible focus states
- Accessible MFA inputs
- Accessible CAPTCHA alternatives where applicable

---

# 106. Mobile Security

Mobile browsers must receive the same backend authorization as desktop.

Do not assume:

```text
mobile = trusted
```

Protect mobile:

- Sessions
- File downloads
- Video access
- Checkout
- Profile
- Admin access

---

# 107. TV Security

TV mode should not expose privileged functionality by default.

TV learning mode may use:

```text
Short-lived session
Limited navigation
Read-only learning interface
```

Admin functionality should not be optimized for public TV displays.

---

# 108. Privacy-Safe Analytics

Analytics should avoid unnecessary sensitive information.

Never send:

- Passwords
- Access tokens
- Payment secrets
- Private messages
- Sensitive student information

Use anonymization/pseudonymization where appropriate.

---

# 109. Security-Friendly Caching

Do not cache private responses publicly.

Private pages such as:

```text
/student/dashboard
/admin
/orders
/profile
```

must use appropriate cache-control policies.

Avoid CDN caching authenticated responses unless carefully designed.

---

# 110. Cache Poisoning Protection

Validate:

- Host headers
- Cache keys
- Query parameters
- Content types

Avoid caching user-specific content under shared cache keys.

---

# 111. DNS Security

Use:

- Trusted DNS provider
- DNSSEC where appropriate
- Registrar account MFA
- Domain lock
- Monitoring for unauthorized DNS changes

Domain account compromise can compromise the entire application.

---

# 112. Email Account Security

Critical email accounts should use:

- MFA
- Strong unique passwords
- Recovery methods
- Restricted access
- Login monitoring

Because password resets depend on email security.

---

# 113. Security Incident Response

Incident process:

```text
Detect
  ↓
Triage
  ↓
Contain
  ↓
Investigate
  ↓
Eradicate
  ↓
Recover
  ↓
Verify
  ↓
Document
  ↓
Improve
```

---

# 114. Incident Severity

### P0 — Critical

Examples:

- Active admin compromise
- Database breach
- Major payment compromise
- Secret exposure

### P1 — High

Examples:

- Privilege escalation
- Major API vulnerability
- Sensitive data exposure

### P2 — Medium

Examples:

- Limited information disclosure
- Security misconfiguration

### P3 — Low

Examples:

- Minor hardening issue

---

# 115. Account Compromise Response

If an account is compromised:

```text
Revoke sessions
↓
Reset password
↓
Revoke tokens
↓
Disable suspicious sessions
↓
Review audit logs
↓
Review MFA
↓
Notify user
↓
Investigate affected resources
```

For privileged accounts, escalate immediately according to incident procedures.

---

# 116. Secret Rotation

Rotate:

- API keys
- Database credentials
- Authentication secrets
- Webhook secrets
- Storage credentials
- Encryption keys where supported

Rotation must have documented procedures.

---

# 117. Vulnerability Management

Each vulnerability should have:

```text
ID
Severity
Affected component
Description
Impact
Fix
Owner
Deadline
Status
Verification
```

Critical vulnerabilities should receive immediate attention.

---

# 118. Security Patch Policy

Security patches should be prioritized according to:

```text
Exploitability
Impact
Exposure
Asset sensitivity
Availability of mitigation
```

Internet-facing critical vulnerabilities should be addressed urgently.

---

# 119. Secure Development Workflow

Developer workflow:

```text
Requirement
   ↓
Threat Modeling
   ↓
Secure Design
   ↓
Implementation
   ↓
Code Review
   ↓
Automated Security Checks
   ↓
Staging
   ↓
Security Testing
   ↓
Production
   ↓
Monitoring
```

---

# 120. Security Code Review Checklist

Reviewers must check:

- Authentication
- Authorization
- Input validation
- Output encoding
- SQL safety
- XSS
- CSRF
- SSRF
- File upload
- Secrets
- Logging
- Error handling
- Rate limits
- Sensitive data exposure
- Race conditions
- Payment integrity

---

# 121. Race Condition Protection

Protect operations such as:

- Coupon redemption
- Enrollment
- Inventory/seat allocation
- Payment confirmation
- Refund
- Download limits

Use:

```text
Transactions
Locks where necessary
Unique constraints
Idempotency keys
Atomic operations
```

---

# 122. Database Constraints

Use database constraints to protect integrity:

```text
PRIMARY KEY
FOREIGN KEY
UNIQUE
NOT NULL
CHECK
```

Application validation should not be the only line of defense.

---

# 123. Business Logic Security

Security must cover business rules.

Examples:

A student must not:

```text
change their own enrollment status
change course price
mark payment successful
issue certificates
change another user's role
```

Even if the frontend hides those buttons.

---

# 124. Secure State Transitions

Important entities should have controlled states.

Example:

```text
Order:
Pending → Paid → Completed
Pending → Failed
Paid → Refunded
```

The API must reject invalid transitions.

---

# 125. Auditability of Business Events

Record important business events:

```text
Enrollment created
Payment verified
Refund issued
Course published
Certificate issued
Role changed
Account disabled
```

This helps both security and operational investigation.

---

# 126. Security Headers & HTTPS Deployment Checklist

Before production:

- [ ] HTTPS active
- [ ] HTTP redirects to HTTPS
- [ ] HSTS configured
- [ ] Secure cookies
- [ ] CSP reviewed
- [ ] CORS restricted
- [ ] Clickjacking protection
- [ ] MIME sniffing disabled
- [ ] Referrer policy configured
- [ ] Permissions policy configured

---

# 127. Production Security Checklist

## Authentication

- [ ] Argon2id/bcrypt password hashing
- [ ] MFA for privileged users
- [ ] Secure sessions
- [ ] Password reset protection
- [ ] Login rate limiting
- [ ] Session revocation

## Authorization

- [ ] RBAC
- [ ] Fine-grained permissions
- [ ] Object-level authorization
- [ ] Backend enforcement
- [ ] Least privilege

## API

- [ ] Validation
- [ ] Rate limiting
- [ ] CORS
- [ ] CSRF protection where required
- [ ] Secure response schemas
- [ ] Error sanitization

## Database

- [ ] Parameterized queries
- [ ] Least-privilege DB user
- [ ] Encryption
- [ ] Backups
- [ ] Restore testing

## Files

- [ ] Upload validation
- [ ] Malware scanning
- [ ] Private storage
- [ ] Signed URLs
- [ ] Safe filenames

## Payments

- [ ] Provider-hosted/tokenized payment
- [ ] Backend amount validation
- [ ] Webhook signature verification
- [ ] Idempotency
- [ ] Payment audit logs

## Infrastructure

- [ ] HTTPS
- [ ] WAF
- [ ] DDoS protection
- [ ] Firewall
- [ ] Monitoring
- [ ] Secure CI/CD

---

# 128. Security Acceptance Criteria

The application is not considered production-ready until:

1. Unauthorized users cannot access protected resources.
2. Students cannot access other students' private data.
3. Workers cannot exceed assigned permissions.
4. Managers cannot access unrestricted super-admin functions.
5. Admin actions are auditable.
6. Passwords are securely hashed.
7. Privileged accounts use MFA.
8. Sessions can be revoked.
9. APIs enforce authorization server-side.
10. SQL injection defenses are verified.
11. XSS defenses are verified.
12. CSRF defenses are verified where applicable.
13. File uploads are securely validated.
14. Private files are not publicly accessible.
15. Payment status cannot be forged from the frontend.
16. Payment webhooks are cryptographically verified.
17. Secrets are absent from source control.
18. Production debug mode is disabled.
19. Backups are automated and restore-tested.
20. Security monitoring and alerting are operational.
21. Critical dependencies are continuously monitored.
22. Security-sensitive CI/CD checks are active.
23. Critical security findings are resolved before launch.
24. Incident response procedures are documented.
25. Security controls work across Bangla and English interfaces.

---

# 129. Security Testing Matrix by User Type

| Security Test | Student | Worker | Manager | Admin | Super Admin |
|---|---:|---:|---:|---:|---:|
| Own data access | ✓ | ✓ | ✓ | ✓ | ✓ |
| Other-user data denial | ✓ | ✓ | ✓ | ✓ | ✓ |
| Privilege escalation test | ✓ | ✓ | ✓ | ✓ | ✓ |
| API authorization | ✓ | ✓ | ✓ | ✓ | ✓ |
| File access control | ✓ | ✓ | ✓ | ✓ | ✓ |
| Session revocation | ✓ | ✓ | ✓ | ✓ | ✓ |
| MFA | Optional/Policy | Required | Required | Required | Required |
| Audit logging | ✓ | ✓ | ✓ | ✓ | ✓ |

---

# 130. Recommended Security Stack

The exact tools may vary with the final technology stack, but the architecture should support:

```text
HTTPS/TLS
CDN
WAF
DDoS Protection
Secure Authentication
Argon2id
MFA/WebAuthn
RBAC/ABAC
PostgreSQL
Row-Level Security where appropriate
Private Object Storage
Signed URLs
Redis/Distributed Rate Limiting
SAST
SCA
DAST
Secret Scanning
Container Scanning
Centralized Logging
Security Monitoring
Encrypted Backups
```

---

# 131. Security Documentation

Maintain:

```text
SECURITY.md
THREAT_MODEL.md
INCIDENT_RESPONSE.md
DATA_RETENTION.md
PRIVACY.md
BACKUP_RECOVERY.md
ACCESS_CONTROL_MATRIX.md
SECURITY_CHANGELOG.md
```

Security documentation must be updated when architecture changes.

---

# 132. Security Changelog

Every major security change should record:

```text
Date
Change
Reason
Affected Components
Risk
Migration
Rollback
Reviewer
```

---

# 133. Final Security Architecture Rule

The platform must follow:

```text
Never Trust the Client
Never Trust User Input
Never Trust Role Claims from the Client
Never Trust Payment Status from the Browser
Never Expose Secrets
Never Assume Hidden URLs Are Secure
Never Give More Permissions Than Necessary
Never Make Private Data Public by Default
Never Skip Authorization
Never Skip Audit Logging for Critical Actions
```

---

# 134. Final Security Principle

Emisha Academy should be built as a **defense-in-depth security system**.

Security must exist at every layer:

```text
User
 ↓
Browser
 ↓
Frontend
 ↓
CDN/WAF
 ↓
API
 ↓
Authentication
 ↓
Authorization
 ↓
Business Logic
 ↓
Database
 ↓
Storage
 ↓
Infrastructure
 ↓
Monitoring
 ↓
Incident Response
```

A vulnerability in one layer must not automatically compromise the entire platform.

The target architecture is:

```text
Secure by Default
+
Least Privilege
+
Defense in Depth
+
Continuous Monitoring
+
Auditable Actions
+
Privacy by Design
+
Secure Development
+
Tested Recovery
```

**Emisha Academy Security Status:**  
`Production security must be verified against this document before public launch.`
