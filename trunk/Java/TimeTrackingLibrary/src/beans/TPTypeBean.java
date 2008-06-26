package beans;

/**
 * Represents one row of the TPType table
 * @author manuel, steffen
 */
public class TPTypeBean
{

    private int typId;
    private String name;
    private String symbol;

    /**
     * Creates a new instance of TPTypeBean
     */
    public TPTypeBean()
    {
    }

    /**
     * 
     * @return name
     */
    public String getName()
    {
        return name;
    }
    
    /**
     * 
     * @param name
     */
    public void setName(String name)
    {
        this.name = name;
    }

    /**
     * 
     * @return symbol
     */
    public String getSymbol()
    {
        return symbol;
    }

    /**
     * 
     * @param symbol
     */
    public void setSymbol(String symbol)
    {
        this.symbol = symbol;
    }

    /**
     * 
     * @return typId
     */
    public int getTypId()
    {
        return typId;
    }

    /**
     * 
     * @param typId
     */
    public void setTypId(int typId)
    {
        this.typId = typId;
    }
    
}
