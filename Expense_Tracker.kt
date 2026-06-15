package com.example.budgetapp

import android.app.AlertDialog
import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import android.graphics.Typeface
import java.util.Locale

// Data class representing a single expense item (name, amount, and icon)
data class Expense(
    val name: String,
    val amount: Double,
    val iconRes: Int
)

// Activity that handles displaying, adding, and deleting expenses
class ExpenseTrackerActivity : AppCompatActivity() {

    // List holding all expenses currently in memory
    private val expenseList = mutableListOf(
        Expense("Coffee Shop", 90.0, R.drawable.cofee),
        Expense("Electric Bill", 2400.0, R.drawable.electric),
        Expense("Petrol", 800.0, R.drawable.petrol)
    )

    // UI references
    private lateinit var expenseContainer: LinearLayout
    private lateinit var btnAddExpense: Button
    private lateinit var btnNavToSavings: Button

    // Called when activity is created
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // Load layout for this screen
        setContentView(R.layout.activity_expense_tracker)

        // Link UI components from XML
        expenseContainer = findViewById(R.id.expenseContainer)
        btnAddExpense = findViewById(R.id.btnAddExpense)
        btnNavToSavings = findViewById(R.id.btnNavToSavings)

        // Render initial expense list on screen
        renderExpenses()

        // Button to add a new expense (opens dialog)
        btnAddExpense.setOnClickListener {
            showAddExpenseDialog()
        }

        // Navigate to savings goals screen
        btnNavToSavings.setOnClickListener {
            val intent = Intent(this, SavingGoalsActivity::class.java)
            startActivity(intent)
        }

        // Optional icon navigation to savings screen
        findViewById<ImageView>(R.id.btnGoToSavings)?.setOnClickListener {
            startActivity(Intent(this, SavingGoalsActivity::class.java))
        }

        // Back button to close activity
        findViewById<ImageView>(R.id.btnBack)?.setOnClickListener {
            finish()
        }
    }

    // Rebuilds the full expense list UI
    private fun renderExpenses() {
        expenseContainer.removeAllViews()

        // Add each expense row dynamically
        for (expense in expenseList) {
            addExpenseRow(expense)
        }
    }

    // Creates and adds a single expense row to the UI
    private fun addExpenseRow(expense: Expense) {

        // Divider line between rows
        val divider = View(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                LinearLayout.LayoutParams.MATCH_PARENT, 2
            )
            setBackgroundColor(0xFFEEEEEE.toInt())
        }
        expenseContainer.addView(divider)

        // Main row container (horizontal layout)
        val row = LinearLayout(this).apply {
            orientation = LinearLayout.HORIZONTAL
            gravity = android.view.Gravity.CENTER_VERTICAL
            setPadding(0, 24, 0, 24)
            layoutParams = LinearLayout.LayoutParams(
                LinearLayout.LayoutParams.MATCH_PARENT,
                LinearLayout.LayoutParams.WRAP_CONTENT
            )
        }

        // Expense icon
        val icon = ImageView(this).apply {
            val params = LinearLayout.LayoutParams(80, 80)
            params.marginEnd = 36
            layoutParams = params
            setImageResource(expense.iconRes)
        }

        // Expense name text
        val nameText = TextView(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                0, LinearLayout.LayoutParams.WRAP_CONTENT, 1f
            )
            text = expense.name
            textSize = 15f
            setTextColor(0xFF333333.toInt())
        }

        // Expense amount text (bold)
        val amountText = TextView(this).apply {
            text = formatAmount(expense.amount)
            textSize = 15f
            setTextColor(0xFF1A1A1A.toInt())
            setTypeface(null, Typeface.BOLD)
        }

        // Long press listener for delete action
        row.setOnLongClickListener {
            showDeleteDialog(expense)
            true
        }

        // Add views into row
        row.addView(icon)
        row.addView(nameText)
        row.addView(amountText)

        // Add row into main container
        expenseContainer.addView(row)
    }

    // Dialog for adding a new expense
    private fun showAddExpenseDialog() {

        // Inflate custom dialog layout
        val dialogView = LayoutInflater.from(this).inflate(R.layout.activity_add_expense, null)
        val etName = dialogView.findViewById<EditText>(R.id.etExpenseName)
        val etAmount = dialogView.findViewById<EditText>(R.id.etExpenseAmount)

        // Build alert dialog
        val dialog = AlertDialog.Builder(this)
            .setTitle("Add New Expense")
            .setView(dialogView)
            .setPositiveButton("Add", null)
            .setNegativeButton("Cancel") { d, _ -> d.dismiss() }
            .create()

        // Override positive button to validate input
        dialog.setOnShowListener {
            dialog.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener {

                val name = etName.text.toString().trim()
                val amountStr = etAmount.text.toString().trim()

                // Validate name input
                if (name.isEmpty()) {
                    etName.error = "Please enter a name"
                    return@setOnClickListener
                }

                // Validate amount input
                if (amountStr.isEmpty()) {
                    etAmount.error = "Please enter an amount"
                    return@setOnClickListener
                }

                // Convert string to number safely
                val amount = amountStr.toDoubleOrNull()
                if (amount == null || amount <= 0.0) {
                    etAmount.error = "Enter a valid amount"
                    return@setOnClickListener
                }

                // Create new expense object
                val newExpense = Expense(
                    name = name,
                    amount = amount,
                    iconRes = R.drawable.cofee
                )

                // Add to list and refresh UI
                expenseList.add(newExpense)
                renderExpenses()

                // Show confirmation message
                Toast.makeText(this, "Expense added!", Toast.LENGTH_SHORT).show()
                dialog.dismiss()
            }
        }

        dialog.show()
    }

    // Dialog for deleting an expense
    private fun showDeleteDialog(expense: Expense) {

        AlertDialog.Builder(this)
            .setTitle("Delete Expense")
            .setMessage("Remove \"${expense.name}\" (${formatAmount(expense.amount)})?")
            .setPositiveButton("Delete") { _, _ ->

                // Remove item from list
                expenseList.remove(expense)

                // Refresh UI
                renderExpenses()

                // Show confirmation
                Toast.makeText(this, "${expense.name} removed", Toast.LENGTH_SHORT).show()
            }
            .setNegativeButton("Cancel", null)
            .show()
    }

    // Formats amount into readable currency style (Rands)
    private fun formatAmount(amount: Double): String {
        val formatter = java.text.NumberFormat.getNumberInstance(Locale.US)
        formatter.maximumFractionDigits = 2
        formatter.minimumFractionDigits = 0
        return "R${formatter.format(amount)}"
    }
}
