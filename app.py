from flask import Flask, request, jsonify, send_from_directory
import os
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.base import MIMEBase
from email import encoders

app = Flask(__name__)
UPLOAD_FOLDER = 'uploads'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

# SMTP configuration
SMTP_SERVER = 'smtp.gmail.com'
SMTP_PORT = 587
SMTP_USERNAME = '2ndyearaids@gmail.com'  # Your Gmail address
SMTP_PASSWORD = 'pbcsonugbaomgikp'       # Your Gmail app password
RECIPIENT_EMAIL = 'gmars.gmr@gmail.com'  # Recipient's email

@app.route('/')
def index():
    return send_from_directory('.', 'index.html')

@app.route('/upload', methods=['POST'])
def upload_file():
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'}), 400

    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No selected file'}), 400

    name = request.form.get('name')
    reg_number = request.form.get('regNumber')

    if file:
        filename = file.filename
        file_path = os.path.join(UPLOAD_FOLDER, filename)
        file.save(file_path)

        # Create the email
        msg = MIMEMultipart()
        msg['From'] = SMTP_USERNAME
        msg['To'] = RECIPIENT_EMAIL
        msg['Subject'] = f'Name: {name}, Register Number: {reg_number}'

        body = f'Name: {name}\nRegister Number: {reg_number}'
        msg.attach(MIMEText(body, 'plain'))

        # Attach the file
        attachment = MIMEBase('application', 'octet-stream')
        with open(file_path, 'rb') as f:
            attachment.set_payload(f.read())
        encoders.encode_base64(attachment)
        attachment.add_header('Content-Disposition', f'attachment; filename={filename}')
        msg.attach(attachment)

        # Send the email
        with smtplib.SMTP(SMTP_SERVER, SMTP_PORT) as server:
            server.starttls()
            server.login(SMTP_USERNAME, SMTP_PASSWORD)
            server.send_message(msg)

        os.remove(file_path)  # Clean up the uploaded file

        return jsonify({'message': 'File uploaded and email sent successfully!'})

if __name__ == '__main__':
    app.run(debug=True)
