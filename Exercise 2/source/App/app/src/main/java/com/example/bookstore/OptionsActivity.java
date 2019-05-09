package com.example.bookstore;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

public class OptionsActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_options);

        TextView test = findViewById(R.id.textView10);
        test.setText("Hello, " + GVars.INSTANCE.uName);
    }

    public void logout(View view) {
        startActivity(new Intent(this, MainActivity.class));
        finish();
    }

    protected void goListing(View view) {
        startActivity(new Intent(this, ListingActivity.class));
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.main_menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch(item.getItemId()) {
            case R.id.bkList:
                startActivity(new Intent(this, ListingActivity.class));
                return true;
            case R.id.accnt:
                startActivity(new Intent(this, AccountActivity.class));
                return true;
            case R.id.log:
                GVars.INSTANCE.uName = "";
                GVars.INSTANCE.uID = "0";
                GVars.INSTANCE.logged = false;
                startActivity(new Intent(this, MainActivity.class));
                finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }
}
