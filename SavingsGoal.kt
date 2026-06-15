package com.example.budgetapp

import android.app.AlertDialog
import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.ProgressBar
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity

// Activity that manages creating and displaying savings goals
class SavingGoalsActivity : AppCompatActivity() {

    // UI container that holds all goal cards dynamically
    private lateinit var goalsContainer: LinearLayout

    // Button used to create a new savings goal
    private lateinit var btnCreateGoal: Button

    // Called when activity is created
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // Load layout for savings goals screen
        setContentView(R.layout.activity_savings_goals)

        // Link UI components from XML
        goalsContainer = findViewById(R.id.goalsContainer)
        btnCreateGoal = findViewById(R.id.btnCreateNewGoal)

        // Button to view reports
        val btnViewReports = findViewById<Button>(R.id.btnViewReports)

        // Open dialog to create a new goal
        btnCreateGoal.setOnClickListener {
            showCreateGoalDialog()
        }

        // Navigate to Report screen
        btnViewReports.setOnClickListener {
            startActivity(Intent(this, Report::class.java))
        }

        // Optional icon navigation to Report screen
        findViewById<ImageView>(R.id.btnGoToReports)?.setOnClickListener {
            startActivity(Intent(this, Report::class.java))
        }

        // Back button closes current activity
        findViewById<ImageView>(R.id.btnBack)?.setOnClickListener {
            finish()
        }
    }

    // Shows a dialog for creating a new savings goal
    private fun showCreateGoalDialog() {

        // Create vertical layout for input fields
        val layout = LinearLayout(this)
        layout.orientation = LinearLayout.VERTICAL
        layout.setPadding(50, 30, 50, 30)

        // Input field for goal name
        val etName = EditText(this)
        etName.hint = "Goal Name"

        // Input field for target amount
        val etTarget = EditText(this)
        etTarget.hint = "Target Amount"

        // Input field for current saved amount
        val etCurrent = EditText(this)
        etCurrent.hint = "Current Amount"

        // Add inputs to layout
        layout.addView(etName)
        layout.addView(etTarget)
        layout.addView(etCurrent)

        // Build and show dialog
        AlertDialog.Builder(this)
            .setTitle("Create Savings Goal")
            .setView(layout)
            .setPositiveButton("Add") { _, _ ->

                // Read and clean user input
                val name = etName.text.toString().trim()
                val target = etTarget.text.toString().toDoubleOrNull() ?: 0.0
                val current = etCurrent.text.toString().toDoubleOrNull() ?: 0.0

                // Only add goal if name is valid
                if (name.isNotEmpty()) {
                    addGoalCard(name, current, target)
                }
            }
            .setNegativeButton("Cancel", null)
            .show()
    }

    // Creates and adds a goal card UI element
    private fun addGoalCard(name: String, current: Double, target: Double) {

        // Card container for each goal
        val card = LinearLayout(this)
        card.orientation = LinearLayout.VERTICAL
        card.setPadding(30, 30, 30, 30)

        // Text view for goal name
        val tvName = TextView(this)
        tvName.text = name
        tvName.textSize = 18f

        // Text view for progress amount
        val tvAmount = TextView(this)
        tvAmount.text = "R${current.toInt()} / R${target.toInt()}"

        // Progress bar showing percentage completion
        val progressBar = ProgressBar(this, null, android.R.attr.progressBarStyleHorizontal)
        progressBar.max = 100

        // Calculate progress percentage safely
        val percentage = if (target > 0) ((current / target) * 100).toInt() else 0
        progressBar.progress = percentage

        // Add views to card
        card.addView(tvName)
        card.addView(tvAmount)
        card.addView(progressBar)

        // Add card to main container
        goalsContainer.addView(card)
    }
