package com.example.bookstore;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.URI;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;
import android.content.Intent;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

import android.content.Context;
import android.os.AsyncTask;
import android.widget.TextView;

public class SigninActivity  extends AsyncTask<String, Void, String> {
    private TextView statusField,roleField;
    private Context context;
    private int byGetOrPost = 0;

    //flag 0 means get and 1 means post.(By default it is get.)
    public SigninActivity(Context context,TextView statusField,TextView roleField,int flag) {
        this.context = context;
        this.statusField = statusField;
        this.roleField = roleField;
        byGetOrPost = flag;
    }

    protected void onPreExecute(){

    }

    protected String doInBackground(String... arg0) {
        try {
            String username = arg0[0];
            String password = arg0[1];

            String link="http://undcemcs02.und.edu/~tyler.w.clark/457/2/login.php";
            String data  = URLEncoder.encode("inputUName", "UTF-8") + "=" +
                    URLEncoder.encode(username, "UTF-8");
            data += "&" + URLEncoder.encode("inputPW", "UTF-8") + "=" +
                    URLEncoder.encode(password, "UTF-8");

            URL url = new URL(link);
            URLConnection conn = url.openConnection();

            conn.setDoOutput(true);
            OutputStreamWriter wr = new OutputStreamWriter(conn.getOutputStream());

            wr.write( data );
            wr.flush();

            BufferedReader reader = new BufferedReader(new
                    InputStreamReader(conn.getInputStream()));

            StringBuilder sb = new StringBuilder();
            String line;

            // Read Server Response
            while((line = reader.readLine()) != null) {
                sb.append(line);
                break;
            }
            String txt = sb.toString();
            if(!txt.equals("NotLogged")) {
                GVars.INSTANCE.logged = true;
                String[] split = txt.split("\\s+");
                GVars.INSTANCE.uID = split[0];
                GVars.INSTANCE.uName = split[1];
            }
            return sb.toString();
        } catch(Exception e){
            return new String("Exception: " + e.getMessage());
        }

    }

    protected void onPostExecute(String result){
        if(GVars.INSTANCE.logged) {
            context.startActivity(new Intent(context, OptionsActivity.class));
        } else {
            this.statusField.setText("Login invalid");
        }
    }
}