package com.example.budgetapp

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Patterns
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity

class Register : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)

        val etUsername = findViewById<EditText>(R.id.etUsername)
        val etEmail = findViewById<EditText>(R.id.etEmail)
        val etPassword = findViewById<EditText>(R.id.etPassword)
        val btnRegister = findViewById<Button>(R.id.btnRegister)

        btnRegister.setOnClickListener {
            val username = etUsername.text.toString().trim()
            val email = etEmail.text.toString().trim()
            val password = etPassword.text.toString().trim()

            // Validations
            if (username.isEmpty()) {
                etUsername.error = "Please enter a username"
                return@setOnClickListener
            }
            if (email.isEmpty()) {
                etEmail.error = "Please enter an email"
                return@setOnClickListener
            }
            if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
                etEmail.error = "Invalid email address"
                return@setOnClickListener
            }
            if (password.isEmpty()) {
                etPassword.error = "Please enter a password"
                return@setOnClickListener
            }
            if (password.length < 6) {
                etPassword.error = "you need to enter 6 characters"
                return@setOnClickListener
            }

            // Save to SharedPreferences
            val sharedPref = getSharedPreferences("UserPrefs", Context.MODE_PRIVATE)
            val editor = sharedPref.edit()
            editor.putString(email, password)
            editor.apply()

            Toast.makeText(this, "Registration Successful!", Toast.LENGTH_SHORT).show()
            
            // Navigate to Dashboard
            startActivity(Intent(this, Dashboard::class.java))
            finish()
        }
    }
}
