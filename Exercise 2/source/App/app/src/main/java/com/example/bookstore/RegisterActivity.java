package com.example.bookstore;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.widget.TextView;

import org.w3c.dom.Text;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;


public class RegisterActivity extends AsyncTask<String, Void, String> {

    private Context context;
    private String txt;
    private TextView msg;

    public RegisterActivity(Context context, TextView msg) {
        this.context = context;
        this.msg = msg;
    }

    public void onPreExecute() {

    }

    public String doInBackground(String... arg0){
        try {
            String username = arg0[0];
            String password = arg0[1];

            String link="http://undcemcs02.und.edu/~tyler.w.clark/457/2/register_app.php";
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

            txt = sb.toString();

            return sb.toString();
        } catch(Exception e){
            return new String("Exception: " + e.getMessage());
        }
    }

    protected void onPostExecute(String result) {

    }
}
