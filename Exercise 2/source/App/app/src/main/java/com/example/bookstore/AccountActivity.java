package com.example.bookstore;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.TextView;

public class AccountActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_account);
        TextView v;
        v = findViewById(R.id.textView11);
        v.setText("ID: " + GVars.INSTANCE.uID);
        v = findViewById(R.id.textView12);
        v.setText("Username: " + GVars.INSTANCE.uName);
    }

    //http://undcemcs02.und.edu/~tyler.w.clark/457/2/account_app.php?userID=


}
