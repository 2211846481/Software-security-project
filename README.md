# Software-security-project
# Software Security Project - SNH Secure Web Application

## About
The SNH application is a secure web platform developed as part of the **Introduction to Software Security** course to demonstrate practical defense mechanisms against common web vulnerabilities. 

The application features three secure interfaces:
* **Authentication:** Secure Sign-Up and Log-In interfaces.
* **System Overview:** An "About" interface providing system details.
* **Authorized Actions:** Post-login features allowing authenticated users to send secure text notes and upload specific, validated file types (`jpeg`, `png`, `jpg`, `gif`, `webp`, `pdf`, `doc`, `docx`).

## Technologies Used
- **Framework:** Laravel
- **Database:** MySQL
- **Frontend:** Tailwind CSS

---

## Installation & Setup Steps

Follow these commands in your terminal to set up the project locally:

```bash
# 1. Clone the repository
git clone <repository-url>

# 2. Install dependencies
composer install

# 3. Setup environment configuration
cp .env.example .env
php artisan key:generate

# 4. Run database migrations & link storage
php artisan migrate
php artisan storage:link

# 5. Start the local development server
php artisan serve

---

## Security Hardening Configuration (Required)

To secure the local host environment and mitigate infrastructure-level risks, please apply the following security controls:

### 1. Disable SMB Services (Fixes HIGH-03)
This web application operates strictly over HTTP/HTTPS and does not require local network file sharing. Leaving SMB active exposes the host to remote code execution and credential coercion attacks.

#### For Windows Environments:
1. Press `Win + R`, type `services.msc`, and press **Enter**.
2. Locate the service named **"Server"** (`LanmanServer`).
3. Right-click the service and select **Properties**.
4. Change the *Startup type* to **Disabled**.
5. Click **Stop** to terminate the running service, then click **OK**.

#### For macOS/Linux Environments:
* Ensure that File Sharing (SMB/Samba) is toggled **OFF** in your System Settings / Sharing preferences.

### 2. Network Firewall Hardening
Ensure your host firewall (e.g., Windows Defender Firewall or macOS Packet Filter) is active and strictly blocking inbound traffic on critical SMB ports:
* **Port 139** (NetBIOS)
* **Port 445** (SMB over TCP)