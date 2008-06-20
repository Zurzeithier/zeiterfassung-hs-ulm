package utils;

import java.util.Date;
import java.util.TimeZone;

/**
 *
 * @author manuel
 */
public class LocalizeUtils 
{
    public static Date localizeDate(Date date)
    {
        int offset = TimeZone.getDefault().getOffset(date.getTime());
        
        return new Date(date.getTime() + offset);
    }
}
