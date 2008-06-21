package utils;

import java.security.MessageDigest;

/**
 * Provides security methods
 * @author manuel
 */
public class SecurityUtils
{

    public static String makeMD5Checksum(String s)
    {

        try
        {// Berechnung
            MessageDigest md5 = MessageDigest.getInstance("MD5");
            md5.reset();
            md5.update(s.getBytes());
            byte[] result = md5.digest();

            // Umwandlung
            StringBuffer hexString = new StringBuffer();
            for (int i = 0; i < result.length; ++i)
            {
                hexString.append(Integer.toHexString(0xFF & result[i]));
            }

            return hexString.toString();
        }
        catch (Throwable e)
        {
        }

        return "";
    }

}
