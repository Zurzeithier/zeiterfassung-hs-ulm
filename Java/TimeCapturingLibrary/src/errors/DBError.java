package errors;

/**
 *
 * @author manuel
 */
public class DBError extends Error
{

    public DBError(Throwable cause)
    {
        super(cause);
    }

    public DBError(String message, Throwable cause)
    {
        super(message, cause);
    }

    public DBError(String message)
    {
        super(message);
    }

    public DBError()
    {
    }

}
