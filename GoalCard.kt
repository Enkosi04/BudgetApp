package com.example.budgetapp

import android.os.Bundle
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

// Activity that displays a goal card screen
class GoalCard : AppCompatActivity() {

    // Called when the activity is first created
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // Enables edge-to-edge layout so content can extend behind system bars
        enableEdgeToEdge()

        // Set the layout file for this activity
        setContentView(R.layout.activity_goal_card)

        // Adjust layout padding to avoid overlap with system bars (status/navigation bars)
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->

            // Get system bar insets (top, bottom, left, right)
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())

            // Apply padding so UI elements are not hidden behind system UI
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)

            // Return insets after applying padding
            insets
        }
    }
}
