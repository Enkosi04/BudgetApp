package com.example.budgetapp

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.LinearLayout
import androidx.appcompat.app.AppCompatActivity

class Dashboard : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_dashboard)

        val navAddExpense  = findViewById<LinearLayout>(R.id.navAddExpense)
        val navViewReports = findViewById<LinearLayout>(R.id.navViewReports)
        val navSetGoal     = findViewById<LinearLayout>(R.id.navSetGoal)
        
        val btnToAddExpense = findViewById<Button>(R.id.btnNavigateToAddExpense)
        val btnToTracker = findViewById<Button>(R.id.btnNavigateToExpenseTracker)

        btnToAddExpense.setOnClickListener {
            startActivity(Intent(this, AddExpense::class.java))
        }

        btnToTracker.setOnClickListener {
            startActivity(Intent(this, ExpenseTrackerActivity::class.java))
        }

        // Bottom nav: Add Expense
        navAddExpense.setOnClickListener {
            startActivity(Intent(this, AddExpense::class.java))
        }

        // Bottom nav: View Reports → Report
        navViewReports.setOnClickListener {
            startActivity(Intent(this, Report::class.java))
        }

        // Bottom nav: Set Goal → SavingGoalsActivity
        navSetGoal.setOnClickListener {
            startActivity(Intent(this, SavingGoalsActivity::class.java))
        }
        
        findViewById<android.widget.ImageView>(R.id.btnSettings)?.setOnClickListener {
             startActivity(Intent(this, Setting::class.java))
        }
    }
}
