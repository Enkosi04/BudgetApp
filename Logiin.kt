package com.example.budgetapp

import android.content.Context
import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.util.Patterns
import android.widget.Button
import android.widget.EditText
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity

// Login screen activity for user authentication
class Login : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // Load login UI layout
        setContentView(R.layout.activity_login)

        // UI references for input fields and buttons
        val etEmail      = findViewById<EditText>(R.id.etEmail)
        val etPassword   = findViewById<EditText>(R.id.etPassword)
        val btnLogin     = findViewById<Button>(R.id.btnLogin)
        val btnGoToRegister = findViewById<Button>(R.id.btnGoToRegister)

        // Social media icons
        val imgWhatsapp  = findViewById<ImageView>(R.id.imgWhatsapp)
        val imgInstagram = findViewById<ImageView>(R.id.imgInstagram)
        val imgFacebook  = findViewById<ImageView>(R.id.imgFacebook)

        // Open WhatsApp link in browser/app
        imgWhatsapp.setOnClickListener {
            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://wa.me/")))
        }

        // Open Instagram link
        imgInstagram.setOnClickListener {
            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://www.instagram.com/")))
        }

        // Open Facebook link
        imgFacebook.setOnClickListener {
            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://www.facebook.com/")))
        }

        // Navigate to Register screen
        btnGoToRegister.setOnClickListener {
            startActivity(Intent(this, Register::class.java))
        }

        // Login button click handler
        btnLogin.setOnClickListener {

            // Get user input and remove extra spaces
            val email    = etEmail.text.toString().trim()
            val password = etPassword.text.toString().trim()

            // -------- VALIDATION SECTION --------

            // Check if email is empty
            if (email.isEmpty()) {
                etEmail.error = "Please enter an email"
                return@setOnClickListener
            }

            // Check if email format is valid
            if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
                etEmail.error = "Invalid email address"
                return@setOnClickListener
            }

            // Check if password is empty
            if (password.isEmpty()) {
                etPassword.error = "Please enter a password"
                return@setOnClickListener
            }

            // Check password length requirement
            if (password.length < 6) {
                etPassword.error = "you need to enter 6 characters"
                return@setOnClickListener
            }

            // -------- AUTHENTICATION SECTION --------

            // Retrieve saved user data from SharedPreferences
            val sharedPref = getSharedPreferences("UserPrefs", Context.MODE_PRIVATE)
            val savedPassword = sharedPref.getString(email, null)

            // Check if user exists
            if (savedPassword == null) {
                Toast.makeText(this, "User not found. Please register.", Toast.LENGTH_SHORT).show()

                // Check if password matches saved password
            } else if (savedPassword == password) {
                Toast.makeText(this, "Login Successful!", Toast.LENGTH_SHORT).show()

                // Navigate to Dashboard after successful login
                startActivity(Intent(this, Dashboard::class.java))
                finish()

                // Wrong password case
            } else {
                Toast.makeText(this, "wrong password", Toast.LENGTH_SHORT).show()
            }
        }
    }
