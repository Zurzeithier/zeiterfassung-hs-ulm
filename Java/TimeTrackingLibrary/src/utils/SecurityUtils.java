package utils;

import java.security.MessageDigest;

/**
 * Provides security methods
 * @author manuel, steffen
 */
public class SecurityUtils
{
    /**
     * Converts string into MD5 checksum
     * @param s Input string
     * @return Checksum of string s
     */
    public static String makeMD5Checksum(String s)
    {
        try
        {   
            // calculate
            MessageDigest md5 = MessageDigest.getInstance("MD5");
            md5.reset();
            md5.update(s.getBytes());
            byte[] result = md5.digest();

            // convert
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
