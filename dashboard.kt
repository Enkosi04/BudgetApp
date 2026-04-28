import android.os.Bundle
import android.view.View
import android.widget.ProgressBar
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.widget.addTextChangedListener
import kotlinx.android.synthetic.main.activity_main.*

class MainActivity : AppCompatActivity() {

    // Declare variables for the TextViews and ProgressBar
    private lateinit var tvTotalBalance: TextView
    private lateinit var tvBudgetAmounts: TextView
    private lateinit var tvOverBudgetPercent: TextView
    private lateinit var progressBudgetMain: ProgressBar

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        // Initialize views
        tvTotalBalance = findViewById(R.id.tvTotalBalance)
        tvBudgetAmounts = findViewById(R.id.tvBudgetAmounts)
        tvOverBudgetPercent = findViewById(R.id.tvOverBudgetPercent)
        progressBudgetMain = findViewById(R.id.progressBudgetMain)

        // Setup Bottom Navigation Bar Click Listeners
        val navAddExpense = findViewById<LinearLayout>(R.id.navAddExpense)
        val navViewReports = findViewById<LinearLayout>(R.id.navViewReports)
        val navSetGoal = findViewById<LinearLayout>(R.id.navSetGoal)

        navAddExpense.setOnClickListener {
            // Handle Add Expense action
            Toast.makeText(this, "Add Expense clicked", Toast.LENGTH_SHORT).show()
        }

        navViewReports.setOnClickListener {
            // Handle View Reports action
            Toast.makeText(this, "View Reports clicked", Toast.LENGTH_SHORT).show()
        }

        navSetGoal.setOnClickListener {
            // Handle Set Goal action
            Toast.makeText(this, "Set Goal clicked", Toast.LENGTH_SHORT).show()
        }

        // Simulate data updates on the screen
        updateBudgetData()
    }

    private fun updateBudgetData() {
        // Total Balance (example value)
        tvTotalBalance.text = "R87,500"

        // Budget amounts (example values)
        val totalBudget = 40000
        val currentSpend = 35000
        tvBudgetAmounts.text = "R$totalBudget / R$currentSpend"

        // Over-budget percent (example calculation)
        val overBudgetPercent = (currentSpend / totalBudget.toFloat()) * 100
        tvOverBudgetPercent.text = "${overBudgetPercent.toInt()}%"

        // Update the progress bar
        progressBudgetMain.progress = overBudgetPercent.toInt()
        if (overBudgetPercent > 100) {
            progressBudgetMain.progressTintList = getColorStateList(R.color.colorRed) // Change progress tint color to red
        }

        // Set logic for Transaction Rows (mock data)
        val transactions = listOf(
            Transaction("Groceries", "R840"),
            Transaction("Dining Out", "R560"),
            Transaction("Transport", "R280")
        )

        // Dynamically populate transaction rows (example)
        addTransactionRows(transactions)
    }

    private fun addTransactionRows(transactions: List<Transaction>) {
        // Assuming you have a LinearLayout in your layout to hold the transactions (RecyclerView could be better)
        val transactionContainer = findViewById<LinearLayout>(R.id.transactionContainer)

        transactions.forEach { transaction ->
            val transactionView = layoutInflater.inflate(R.layout.item_transaction, null)
            val nameTextView = transactionView.findViewById<TextView>(R.id.tvTransactionName)
            val amountTextView = transactionView.findViewById<TextView>(R.id.tvTransactionAmount)

            nameTextView.text = transaction.name
            amountTextView.text = transaction.amount

            transactionContainer.addView(transactionView)
        }
    }
}

data class Transaction(val name: String, val amount: String)