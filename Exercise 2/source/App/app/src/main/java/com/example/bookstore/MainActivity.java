package com.example.bookstore;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

public class MainActivity extends Activity {

    private EditText usernameField,passwordField, regU, regP;
    private TextView status, role;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        usernameField = (EditText)findViewById(R.id.editText1);
        passwordField = (EditText)findViewById(R.id.editText2);
        status = (TextView)findViewById(R.id.textView6);
    }

    public void loginPost(View view){
        String username = usernameField.getText().toString();
        String password = passwordField.getText().toString();
        new SigninActivity(this,status,role,1).execute(username,password);
    }

    public void goRegister(View view) {
        setContentView(R.layout.activity_register);
    }

    public void goLogin(View view) {
        setContentView(R.layout.activity_main);
    }

    public void register(View view) {
        regU = findViewById(R.id.editText4);
        regP = findViewById(R.id.editText5);
        String username = regU.getText().toString();
        String password = regP.getText().toString();
        new RegisterActivity(this, status).execute(username,password);
        startActivity(new Intent(this, MainActivity.class));
        finish();
    }
}


