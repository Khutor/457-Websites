package com.example.bookstore;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

public class BookActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_book);
        TextView tv;
        tv = findViewById(R.id.textView);
        tv.setText("ISBN: " + GVars.INSTANCE.bISBN);
        tv = findViewById(R.id.textView8);
        tv.setText("Title: " + GVars.INSTANCE.bTitle);
        tv = findViewById(R.id.textView9);
        tv.setText("Cost: $" + GVars.INSTANCE.bCost);
    }

    protected void backToListing(View v) {
        finish();
    }

}
