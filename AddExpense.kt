ackage com.example.budgetapp

import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import android.content.Intent

class AddExpense : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        // Enable edge-to-edge display
        enableEdgeToEdge()
        setContentView(R.layout.activity_add_expense)

        // Set padding for system bars
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        val etName = findViewById<EditText>(R.id.etExpenseName)
        val etAmount = findViewById<EditText>(R.id.etExpenseAmount)
        val btnSave = findViewById<Button>(R.id.btnSaveExpense)

        btnSave.setOnClickListener {
            val name = etName.text.toString().trim()
            val amountStr = etAmount.text.toString().trim()

            if (name.isEmpty()) {
                etName.error = "Please enter a name"
                return@setOnClickListener
            }

            if (amountStr.isEmpty()) {
                etAmount.error = "Please enter an amount"
                return@setOnClickListener
            }

            val amount = amountStr.toDoubleOrNull()
            if (amount == null || amount <= 0) {
                etAmount.error = "Enter a valid amount"
                return@setOnClickListener
            }

            // Prepare data to send back
            val resultIntent = Intent()
            resultIntent.putExtra("expenseName", name)
            resultIntent.putExtra("expenseAmount", amount)

            setResult(RESULT_OK, resultIntent)
            Toast.makeText(this, "Expense saved!", Toast.LENGTH_SHORT).show()

            // Finish activity and return data
            finish()
        }
    }
}
