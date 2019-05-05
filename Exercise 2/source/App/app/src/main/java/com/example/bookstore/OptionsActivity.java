package com.example.bookstore;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.TextView;

public class OptionsActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_options);

        TextView test = findViewById(R.id.textView10);
        test.setText("Hello " + GVars.INSTANCE.uName);
    }
}
