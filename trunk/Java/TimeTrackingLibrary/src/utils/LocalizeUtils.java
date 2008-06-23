package utils;

import java.util.Date;
import java.util.TimeZone;

/**
 * Provides localization methods
 * @author manuel
 */
public class LocalizeUtils 
{
    /**
     * 
     * @param date
     * @return
     */
    public static Date localizeDate(Date date)
    {
        int offset = TimeZone.getDefault().getOffset(date.getTime());
        
        return new Date(date.getTime() + offset);
    }
}
