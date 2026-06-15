package com.example.budgetapp

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import androidx.appcompat.app.AppCompatActivity

// Activity: Budget Planner screen where user can navigate to reports
class BudgetPlanner : AppCompatActivity() {

    // Called when the activity is created (initial screen setup)
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // Link this activity to its XML layout file
        setContentView(R.layout.activity_budget_planner)

        // Find the "View Report" button from the layout
        val btnViewReport = findViewById<Button>(R.id.btnViewReport)

        // Set click listener for the button
        // When clicked, it navigates user to the Report screen
        btnViewReport.setOnClickListener {

            // Create intent to move from BudgetPlanner to Report activity
            val intent = Intent(this, Report::class.java)

            // Start the Report activity
            startActivity(intent)
        }
    }
}
